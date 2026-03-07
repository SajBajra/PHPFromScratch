<?php

require __DIR__ . '/Database.php';

$config = require __DIR__ . '/db-config.php';

try {
    $db = new Database($config);
    $pdo = $db->getPdo();
} catch (PDOException $e) {
    die('Connection failed: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES));
}

// Create a simple table if it doesn't exist
$pdo->exec("
    CREATE TABLE IF NOT EXISTS items (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )
");

// Insert a sample row (ignore duplicate if run multiple times)
$stmt = $pdo->prepare("INSERT INTO items (name) VALUES (:name)");
$stmt->execute(['name' => 'First item']);

// Select all rows
$rows = $pdo->query("SELECT id, name, created_at FROM items ORDER BY id")->fetchAll();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>PDO basics</title>
</head>
<body>
    <h1>PDO basics</h1>
    <p>Table <code>items</code> created/used. One row inserted, all rows below:</p>
    <table border="1" cellpadding="8">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Created at</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($rows as $row): ?>
                <tr>
                    <td><?php echo htmlspecialchars((string) $row['id'], ENT_QUOTES); ?></td>
                    <td><?php echo htmlspecialchars($row['name'], ENT_QUOTES); ?></td>
                    <td><?php echo htmlspecialchars($row['created_at'], ENT_QUOTES); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <p><a href="16-pdo-connect.php">Back to connection test</a></p>
</body>
</html>

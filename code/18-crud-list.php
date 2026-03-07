<?php

$config = require __DIR__ . '/db-config.php';

try {
    $pdo = new PDO(
        $config['dsn'],
        $config['username'],
        $config['password'],
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
} catch (PDOException $e) {
    die('Connection failed: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES));
}

$pdo->exec("
    CREATE TABLE IF NOT EXISTS items (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )
");

$items = $pdo->query("SELECT id, name, created_at FROM items ORDER BY id")->fetchAll();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Items – list</title>
</head>
<body>
    <h1>Items (mini CRUD)</h1>

    <h2>Add item</h2>
    <form method="post" action="18-crud-add.php">
        <label>
            Name:
            <input type="text" name="name" required>
        </label>
        <button type="submit">Add</button>
    </form>

    <h2>All items</h2>
    <?php if (empty($items)): ?>
        <p>No items yet. Add one above.</p>
    <?php else: ?>
        <table border="1" cellpadding="8">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $row): ?>
                    <tr>
                        <td><?php echo htmlspecialchars((string) $row['id'], ENT_QUOTES); ?></td>
                        <td><?php echo htmlspecialchars($row['name'], ENT_QUOTES); ?></td>
                        <td><?php echo htmlspecialchars($row['created_at'], ENT_QUOTES); ?></td>
                        <td>
                            <a href="18-crud-edit.php?id=<?php echo (int) $row['id']; ?>">Edit</a>
                            |
                            <a href="18-crud-delete.php?id=<?php echo (int) $row['id']; ?>">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</body>
</html>

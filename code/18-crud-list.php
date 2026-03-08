<?php

session_start();

if (is_file(__DIR__ . '/../vendor/autoload.php')) {
    require __DIR__ . '/../vendor/autoload.php';
} else {
    require __DIR__ . '/Database.php';
}
$config = require __DIR__ . '/db-config.php';

try {
    $db = new Database($config);
    $pdo = $db->getPdo();
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

$flash = $_SESSION['crud_flash'] ?? '';
$error = $_SESSION['crud_error'] ?? '';
$addName = $_SESSION['crud_name'] ?? '';
unset($_SESSION['crud_flash'], $_SESSION['crud_error'], $_SESSION['crud_name']);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Items – list</title>
</head>
<body>
    <h1>Items (mini CRUD)</h1>

    <?php if ($flash !== ''): ?>
        <p style="color: green;"><?php echo htmlspecialchars($flash, ENT_QUOTES); ?></p>
    <?php endif; ?>
    <?php if ($error !== ''): ?>
        <p style="color: red;"><?php echo htmlspecialchars($error, ENT_QUOTES); ?></p>
    <?php endif; ?>

    <h2>Add item</h2>
    <form method="post" action="18-crud-add.php">
        <label>
            Name:
            <input type="text" name="name" value="<?php echo htmlspecialchars($addName, ENT_QUOTES); ?>" maxlength="255" required>
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
                            <a href="18-crud-delete-confirm.php?id=<?php echo (int) $row['id']; ?>">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</body>
</html>

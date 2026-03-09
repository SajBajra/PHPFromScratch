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
    CREATE TABLE IF NOT EXISTS todos (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        done TINYINT(1) NOT NULL DEFAULT 0,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )
");

$todos = $pdo->query("SELECT id, title, done, created_at FROM todos ORDER BY done ASC, id DESC")->fetchAll();

$flash = $_SESSION['todo_flash'] ?? '';
$error = $_SESSION['todo_error'] ?? '';
$addTitle = $_SESSION['todo_title'] ?? '';
unset($_SESSION['todo_flash'], $_SESSION['todo_error'], $_SESSION['todo_title']);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Todo list</title>
</head>
<body>
    <h1>Todo list</h1>

    <?php if ($flash !== ''): ?>
        <p style="color: green;"><?php echo htmlspecialchars($flash, ENT_QUOTES); ?></p>
    <?php endif; ?>
    <?php if ($error !== ''): ?>
        <p style="color: red;"><?php echo htmlspecialchars($error, ENT_QUOTES); ?></p>
    <?php endif; ?>

    <h2>Add todo</h2>
    <form method="post" action="22-todo-add.php">
        <label>
            Title:
            <input type="text" name="title" value="<?php echo htmlspecialchars($addTitle, ENT_QUOTES); ?>" maxlength="255" required>
        </label>
        <button type="submit">Add</button>
    </form>

    <h2>Tasks</h2>
    <?php if (empty($todos)): ?>
        <p>No todos yet. Add one above.</p>
    <?php else: ?>
        <ul>
            <?php foreach ($todos as $todo): ?>
                <li>
                    <form method="post" action="22-todo-toggle.php" style="display: inline;">
                        <input type="hidden" name="id" value="<?php echo (int) $todo['id']; ?>">
                        <input type="checkbox" <?php echo (int) $todo['done'] ? 'checked' : ''; ?> onchange="this.form.submit()">
                    </form>
                    <span style="<?php echo (int) $todo['done'] ? 'text-decoration: line-through; color: #888;' : ''; ?>"><?php echo htmlspecialchars($todo['title'], ENT_QUOTES); ?></span>
                    <a href="22-todo-delete-confirm.php?id=<?php echo (int) $todo['id']; ?>">Delete</a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</body>
</html>

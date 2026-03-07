<?php

session_start();

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

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id < 1) {
    header('Location: 22-todo-list.php');
    exit;
}

$stmt = $pdo->prepare("SELECT id, title FROM todos WHERE id = :id");
$stmt->execute(['id' => $id]);
$todo = $stmt->fetch();

if (!$todo) {
    header('Location: 22-todo-list.php');
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Confirm delete todo</title>
</head>
<body>
    <h1>Confirm delete</h1>
    <p>Delete "<strong><?php echo htmlspecialchars($todo['title'], ENT_QUOTES); ?></strong>"?</p>
    <form method="post" action="22-todo-delete.php">
        <input type="hidden" name="id" value="<?php echo (int) $todo['id']; ?>">
        <button type="submit">Delete</button>
    </form>
    <p><a href="22-todo-list.php">Cancel</a></p>
</body>
</html>

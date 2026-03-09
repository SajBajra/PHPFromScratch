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

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id < 1) {
    header('Location: 18-crud-list.php');
    exit;
}

$stmt = $pdo->prepare("SELECT id, name FROM items WHERE id = :id");
$stmt->execute(['id' => $id]);
$item = $stmt->fetch();

if (!$item) {
    header('Location: 18-crud-list.php');
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Confirm delete</title>
</head>
<body>
    <h1>Confirm delete</h1>
    <p>Are you sure you want to delete <strong><?php echo htmlspecialchars($item['name'], ENT_QUOTES); ?></strong>?</p>
    <form method="post" action="18-crud-delete.php">
        <input type="hidden" name="id" value="<?php echo (int) $item['id']; ?>">
        <button type="submit">Delete</button>
    </form>
    <p><a href="18-crud-list.php">Cancel</a></p>
</body>
</html>

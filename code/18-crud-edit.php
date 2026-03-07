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

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id < 1) {
    header('Location: 18-crud-list.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    if ($name !== '') {
        $stmt = $pdo->prepare("UPDATE items SET name = :name WHERE id = :id");
        $stmt->execute(['name' => $name, 'id' => $id]);
    }
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
    <title>Edit item</title>
</head>
<body>
    <h1>Edit item</h1>
    <form method="post" action="">
        <label>
            Name:
            <input type="text" name="name" value="<?php echo htmlspecialchars($item['name'], ENT_QUOTES); ?>" required>
        </label>
        <button type="submit">Save</button>
    </form>
    <p><a href="18-crud-list.php">Back to list</a></p>
</body>
</html>

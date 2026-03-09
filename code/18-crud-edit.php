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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    if ($name === '') {
        $_SESSION['crud_error'] = 'Name is required.';
        header('Location: 18-crud-edit.php?id=' . $id);
        exit;
    }
    if (strlen($name) > 255) {
        $_SESSION['crud_error'] = 'Name must be at most 255 characters.';
        header('Location: 18-crud-edit.php?id=' . $id);
        exit;
    }
    $stmt = $pdo->prepare("UPDATE items SET name = :name WHERE id = :id");
    $stmt->execute(['name' => $name, 'id' => $id]);
    $_SESSION['crud_flash'] = 'Item updated.';
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

$error = $_SESSION['crud_error'] ?? '';
unset($_SESSION['crud_error']);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit item</title>
</head>
<body>
    <h1>Edit item</h1>
    <?php if ($error !== ''): ?>
        <p style="color: red;"><?php echo htmlspecialchars($error, ENT_QUOTES); ?></p>
    <?php endif; ?>
    <form method="post" action="">
        <label>
            Name:
            <input type="text" name="name" value="<?php echo htmlspecialchars($item['name'], ENT_QUOTES); ?>" maxlength="255" required>
        </label>
        <button type="submit">Save</button>
    </form>
    <p><a href="18-crud-list.php">Back to list</a></p>
</body>
</html>

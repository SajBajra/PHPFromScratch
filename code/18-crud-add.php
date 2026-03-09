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

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: 18-crud-list.php');
    exit;
}

$name = trim($_POST['name'] ?? '');

if ($name === '') {
    $_SESSION['crud_error'] = 'Name is required.';
    $_SESSION['crud_name'] = $_POST['name'] ?? '';
    header('Location: 18-crud-list.php');
    exit;
}

if (strlen($name) > 255) {
    $_SESSION['crud_error'] = 'Name must be at most 255 characters.';
    $_SESSION['crud_name'] = $name;
    header('Location: 18-crud-list.php');
    exit;
}

$stmt = $pdo->prepare("INSERT INTO items (name) VALUES (:name)");
$stmt->execute(['name' => $name]);

$_SESSION['crud_flash'] = 'Item added.';
header('Location: 18-crud-list.php');
exit;

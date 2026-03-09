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

$id = isset($_POST['id']) ? (int) $_POST['id'] : (isset($_GET['id']) ? (int) $_GET['id'] : 0);

if ($id >= 1) {
    $stmt = $pdo->prepare("DELETE FROM items WHERE id = :id");
    $stmt->execute(['id' => $id]);
    $_SESSION['crud_flash'] = 'Item deleted.';
}

header('Location: 18-crud-list.php');
exit;

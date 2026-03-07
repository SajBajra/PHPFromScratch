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

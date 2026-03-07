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

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: 18-crud-list.php');
    exit;
}

$name = trim($_POST['name'] ?? '');

if ($name === '') {
    header('Location: 18-crud-list.php');
    exit;
}

$stmt = $pdo->prepare("INSERT INTO items (name) VALUES (:name)");
$stmt->execute(['name' => $name]);

header('Location: 18-crud-list.php');
exit;

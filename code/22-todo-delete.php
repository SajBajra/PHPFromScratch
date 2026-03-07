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

$id = isset($_POST['id']) ? (int) $_POST['id'] : 0;

if ($id >= 1) {
    $pdo->prepare("DELETE FROM todos WHERE id = :id")->execute(['id' => $id]);
    $_SESSION['todo_flash'] = 'Todo deleted.';
}

header('Location: 22-todo-list.php');
exit;

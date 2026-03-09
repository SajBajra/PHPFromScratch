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

$id = isset($_POST['id']) ? (int) $_POST['id'] : 0;

if ($id >= 1) {
    $pdo->prepare("DELETE FROM todos WHERE id = :id")->execute(['id' => $id]);
    $_SESSION['todo_flash'] = 'Todo deleted.';
}

header('Location: 22-todo-list.php');
exit;

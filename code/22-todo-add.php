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
    header('Location: 22-todo-list.php');
    exit;
}

$title = trim($_POST['title'] ?? '');

if ($title === '') {
    $_SESSION['todo_error'] = 'Title is required.';
    $_SESSION['todo_title'] = $_POST['title'] ?? '';
    header('Location: 22-todo-list.php');
    exit;
}

if (strlen($title) > 255) {
    $_SESSION['todo_error'] = 'Title must be at most 255 characters.';
    $_SESSION['todo_title'] = $title;
    header('Location: 22-todo-list.php');
    exit;
}

$stmt = $pdo->prepare("INSERT INTO todos (title, done) VALUES (:title, 0)");
$stmt->execute(['title' => $title]);

$_SESSION['todo_flash'] = 'Todo added.';
header('Location: 22-todo-list.php');
exit;

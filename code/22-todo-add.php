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

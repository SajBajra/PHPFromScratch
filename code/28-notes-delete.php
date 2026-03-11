<?php

require __DIR__ . '/includes/require-auth.php';
require_once __DIR__ . '/helpers.php';

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

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !csrf_verify()) {
    header('Location: 28-notes-list.php');
    exit;
}

$id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
$userId = (int) ($_SESSION['auth_user_id'] ?? 0);

if ($id >= 1) {
    $stmt = $pdo->prepare("
        DELETE FROM notes_app_notes
        WHERE id = :id AND user_id = :user_id
    ");
    $stmt->execute(['id' => $id, 'user_id' => $userId]);
    $_SESSION['notes_flash'] = 'Note deleted.';
}

header('Location: 28-notes-list.php');
exit;


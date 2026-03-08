<?php

header('Content-Type: application/json; charset=utf-8');

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
    http_response_code(500);
    echo json_encode(['error' => 'Database connection failed']);
    exit;
}

$items = $pdo->query("SELECT id, name, created_at FROM items ORDER BY id")->fetchAll();

// Convert created_at to string for JSON
foreach ($items as &$row) {
    $row['id'] = (int) $row['id'];
}
unset($row);

echo json_encode(['items' => $items]);

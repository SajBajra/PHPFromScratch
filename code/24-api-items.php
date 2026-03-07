<?php

header('Content-Type: application/json; charset=utf-8');

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

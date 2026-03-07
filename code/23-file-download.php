<?php

$uploadDir = __DIR__ . '/../uploads/';
$name = $_GET['f'] ?? '';

if ($name === '' || preg_match('/[^a-zA-Z0-9._-]/', $name) || strpos($name, '..') !== false) {
    header('HTTP/1.1 400 Bad Request');
    exit('Invalid file name');
}

$path = realpath($uploadDir . $name);

if ($path === false || !is_file($path) || strpos($path, realpath($uploadDir)) !== 0) {
    header('HTTP/1.1 404 Not Found');
    exit('File not found');
}

$mimes = [
    'jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg', 'png' => 'image/png',
    'gif' => 'image/gif', 'pdf' => 'application/pdf',
];
$ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
$mime = $mimes[$ext] ?? 'application/octet-stream';

header('Content-Type: ' . $mime);
header('Content-Disposition: inline; filename="' . basename($name) . '"');
readfile($path);
exit;

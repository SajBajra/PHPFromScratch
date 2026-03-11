<?php

require __DIR__ . '/includes/require-auth.php';
require_once __DIR__ . '/helpers.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !csrf_verify()) {
    header('Location: 29-gallery.php');
    exit;
}

$filename = $_POST['filename'] ?? '';

// Basic safety: no slashes or directory traversal.
if ($filename === '' || strpos($filename, '/') !== false || strpos($filename, '\\') !== false || $filename === '.' || $filename === '..') {
    $_SESSION['gallery_flash'] = 'Invalid file name.';
    header('Location: 29-gallery.php');
    exit;
}

$uploadDir = __DIR__ . '/../uploads/';
$path = $uploadDir . $filename;

if (is_file($path)) {
    if (@unlink($path)) {
        $_SESSION['gallery_flash'] = 'Image deleted.';
    } else {
        $_SESSION['gallery_flash'] = 'Could not delete image.';
    }
} else {
    $_SESSION['gallery_flash'] = 'Image not found.';
}

header('Location: 29-gallery.php');
exit;


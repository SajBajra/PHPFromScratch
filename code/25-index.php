<?php

$page = $_GET['page'] ?? 'home';
$page = preg_replace('/[^a-z0-9_-]/', '', $page);
if ($page === '') {
    $page = 'home';
}

$allowed = ['home'];
$path = __DIR__ . '/25-pages/' . $page . '.php';

if (!in_array($page, $allowed, true) || !is_file($path)) {
    $path = __DIR__ . '/25-pages/404.php';
}

require $path;

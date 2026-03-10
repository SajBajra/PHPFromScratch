<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['auth_logged_in']) || $_SESSION['auth_logged_in'] !== true) {
    // Remember where the user was trying to go, so we can redirect back after login.
    if (!empty($_SERVER['REQUEST_URI'])) {
        $_SESSION['auth_redirect_to'] = $_SERVER['REQUEST_URI'];
    }
    header('Location: 26-login.php');
    exit;
}

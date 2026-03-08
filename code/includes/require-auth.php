<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['auth_logged_in']) || $_SESSION['auth_logged_in'] !== true) {
    header('Location: 26-login.php');
    exit;
}

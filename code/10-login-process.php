<?php

session_start();

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

// Very simple hard-coded credentials for learning purposes only.
$validUsername = 'saj';
$validPassword = 'secret123';

if (trim($username) === '' || trim($password) === '') {
    $_SESSION['login_error'] = 'Username and password are required.';
    header('Location: 10-login-form.php');
    exit;
}

if ($username === $validUsername && $password === $validPassword) {
    $_SESSION['logged_in'] = true;
    $_SESSION['username'] = $username;
    header('Location: 10-dashboard.php');
    exit;
}

$_SESSION['login_error'] = 'Invalid username or password.';
header('Location: 10-login-form.php');
exit;


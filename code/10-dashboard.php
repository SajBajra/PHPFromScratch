<?php

session_start();

if (empty($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: 10-login-form.php');
    exit;
}

$username = $_SESSION['username'] ?? 'guest';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard (protected)</title>
</head>
<body>
    <h1>Dashboard (protected)</h1>

    <p>Welcome, <?php echo htmlspecialchars($username, ENT_QUOTES); ?>. You are logged in.</p>

    <p><a href="10-logout.php">Log out</a></p>
</body>
</html>


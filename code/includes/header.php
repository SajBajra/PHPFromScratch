<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$layoutTitle = $layoutTitle ?? 'PHP From Scratch';
$authLoggedIn = !empty($_SESSION['auth_logged_in']) && $_SESSION['auth_logged_in'] === true;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($layoutTitle, ENT_QUOTES); ?></title>
    <style>
        body { font-family: sans-serif; max-width: 800px; margin: 0 auto; padding: 1rem; }
        nav { margin-bottom: 1.5rem; }
        nav a { margin-right: 1rem; }
    </style>
</head>
<body>
    <nav>
        <a href="25-index.php">Home</a>
        <a href="18-crud-list.php">Items</a>
        <a href="22-todo-list.php">Todos</a>
        <a href="27-blog-list.php">Blog</a>
        <?php if ($authLoggedIn): ?>
            <a href="26-dashboard.php">Dashboard</a>
            <a href="26-logout.php">Log out</a>
        <?php else: ?>
            <a href="26-register.php">Register</a>
            <a href="26-login.php">Log in</a>
        <?php endif; ?>
    </nav>

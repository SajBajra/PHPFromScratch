<?php

session_start();

// If already logged in, send straight to the protected page.
if (!empty($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    header('Location: 10-dashboard.php');
    exit;
}

$error = $_SESSION['login_error'] ?? '';
unset($_SESSION['login_error']);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login example</title>
</head>
<body>
    <h1>Login example</h1>

    <?php if ($error !== ''): ?>
        <p style="color: red;"><?php echo htmlspecialchars($error, ENT_QUOTES); ?></p>
    <?php endif; ?>

    <form method="post" action="10-login-process.php">
        <div>
            <label>
                Username:
                <input type="text" name="username">
            </label>
        </div>
        <div>
            <label>
                Password:
                <input type="password" name="password">
            </label>
        </div>
        <button type="submit">Log in</button>
    </form>
</body>
</html>


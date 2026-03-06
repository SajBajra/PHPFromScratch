<?php

// Read any existing cookie value first.
$nameCookie = $_COOKIE['name'] ?? '';

// Handle form submission to set the cookie.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newName = $_POST['name'] ?? '';

    if (trim($newName) !== '') {
        // Set cookie for 7 days.
        setcookie('name', $newName, time() + 7 * 24 * 60 * 60, '/');
        // Update local variable so the change is visible right away on reload.
        $nameCookie = $newName;
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Simple cookie example</title>
</head>
<body>
    <h1>Simple cookie example</h1>

    <?php if ($nameCookie !== ''): ?>
        <p>Welcome back, <?php echo htmlspecialchars($nameCookie, ENT_QUOTES); ?>! (read from cookie)</p>
    <?php else: ?>
        <p>We don't know your name yet.</p>
    <?php endif; ?>

    <form method="post" action="">
        <label>
            Your name:
            <input type="text" name="name" value="<?php echo htmlspecialchars($nameCookie, ENT_QUOTES); ?>">
        </label>
        <button type="submit">Save in cookie</button>
    </form>

    <p><a href="11-simple-cookie-clear.php">Clear the cookie</a></p>
</body>
</html>


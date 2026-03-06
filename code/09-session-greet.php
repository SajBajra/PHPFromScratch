<?php

session_start();

$name = $_SESSION['user_name'] ?? '';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Greeting from session</title>
</head>
<body>
    <h1>Greeting from session</h1>

    <?php if ($name === ''): ?>
        <p>We don't know your name yet.</p>
        <p><a href="09-session-set-name.php">Set your name</a></p>
    <?php else: ?>
        <p>Hello again, <?php echo htmlspecialchars($name, ENT_QUOTES); ?>!</p>
        <p><a href="09-session-set-name.php">Change your name</a></p>
    <?php endif; ?>
</body>
</html>


<?php

session_start();

$name = $_POST['name'] ?? '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (trim($name) === '') {
        $error = 'Name is required.';
    } else {
        $_SESSION['user_name'] = $name;
        header('Location: 09-session-greet.php');
        exit;
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Set your name (session)</title>
</head>
<body>
    <h1>Set your name (session)</h1>

    <?php if ($error !== ''): ?>
        <p style="color: red;"><?php echo htmlspecialchars($error, ENT_QUOTES); ?></p>
    <?php endif; ?>

    <form method="post" action="">
        <label>
            Your name:
            <input type="text" name="name" value="<?php echo htmlspecialchars($name, ENT_QUOTES); ?>">
        </label>
        <button type="submit">Save name</button>
    </form>

    <p><a href="09-session-greet.php">Go to greeting page</a></p>
</body>
</html>


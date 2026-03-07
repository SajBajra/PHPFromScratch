<?php

$message = '';
$submitted = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $submitted = true;
    $password = $_POST['password'] ?? '';

    if ($password === '') {
        $message = 'Enter a password to see its hash.';
    } else {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $verified = password_verify($password, $hash);
        $message = 'Hash: ' . htmlspecialchars($hash, ENT_QUOTES) . '<br>Verified: ' . ($verified ? 'yes' : 'no');
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Password hashing demo</title>
</head>
<body>
    <h1>Password hashing demo</h1>
    <p>PHP’s <code>password_hash()</code> and <code>password_verify()</code> are the recommended way to store and check passwords. Never store plain-text passwords.</p>

    <form method="post" action="">
        <label>
            Password:
            <input type="password" name="password">
        </label>
        <button type="submit">Generate hash</button>
    </form>

    <?php if ($submitted && $message !== ''): ?>
        <p><?php echo $message; ?></p>
    <?php endif; ?>
</body>
</html>

<?php

$name = $_POST['name'] ?? '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (trim($name) === '') {
        $error = 'Name is required.';
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Simple POST Form</title>
</head>
<body>
    <h1>Simple POST Form</h1>

    <?php if ($error !== ''): ?>
        <p style="color: red;"><?php echo htmlspecialchars($error, ENT_QUOTES); ?></p>
    <?php endif; ?>

    <form method="post" action="">
        <label>
            Your name:
            <input type="text" name="name" value="<?php echo htmlspecialchars($name, ENT_QUOTES); ?>">
        </label>
        <button type="submit">Say hello</button>
    </form>

    <?php if ($name !== ''): ?>
        <p>Hello, <?php echo htmlspecialchars($name, ENT_QUOTES); ?>!</p>
    <?php endif; ?>
</body>
</html>


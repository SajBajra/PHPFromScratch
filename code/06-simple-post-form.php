<?php

// If the form was submitted, read the "name" from the POST body.
$name = $_POST['name'] ?? '';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Simple POST Form</title>
</head>
<body>
    <h1>Simple POST Form</h1>

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


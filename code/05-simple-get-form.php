<?php

// If the form was submitted, read the "name" from the query string.
$name = $_GET['name'] ?? '';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Simple GET Form</title>
</head>
<body>
    <h1>Simple GET Form</h1>

    <form method="get" action="">
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


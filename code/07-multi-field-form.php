<?php

$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';
$age = $_POST['age'] ?? '';
$message = $_POST['message'] ?? '';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (trim($name) === '') {
        $errors[] = 'Name is required.';
    }

    if (trim($email) === '') {
        $errors[] = 'Email is required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Email is not valid.';
    }

    if (trim($age) !== '' && !ctype_digit($age)) {
        $errors[] = 'Age must be a whole number if provided.';
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Multi-field POST Form</title>
</head>
<body>
    <h1>Multi-field POST Form</h1>

    <?php if (!empty($errors)): ?>
        <ul style="color: red;">
            <?php foreach ($errors as $error): ?>
                <li><?php echo htmlspecialchars($error, ENT_QUOTES); ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <form method="post" action="">
        <div>
            <label>
                Name (required):
                <input type="text" name="name" value="<?php echo htmlspecialchars($name, ENT_QUOTES); ?>">
            </label>
        </div>

        <div>
            <label>
                Email (required):
                <input type="email" name="email" value="<?php echo htmlspecialchars($email, ENT_QUOTES); ?>">
            </label>
        </div>

        <div>
            <label>
                Age (optional):
                <input type="text" name="age" value="<?php echo htmlspecialchars($age, ENT_QUOTES); ?>">
            </label>
        </div>

        <div>
            <label>
                Message (optional):
                <textarea name="message" rows="4" cols="40"><?php echo htmlspecialchars($message, ENT_QUOTES); ?></textarea>
            </label>
        </div>

        <button type="submit">Submit</button>
    </form>

    <?php if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($errors)): ?>
        <h2>Submitted data</h2>
        <p><strong>Name:</strong> <?php echo htmlspecialchars($name, ENT_QUOTES); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($email, ENT_QUOTES); ?></p>
        <p><strong>Age:</strong> <?php echo htmlspecialchars($age, ENT_QUOTES); ?></p>
        <p><strong>Message:</strong> <?php echo nl2br(htmlspecialchars($message, ENT_QUOTES)); ?></p>
    <?php endif; ?>
</body>
</html>


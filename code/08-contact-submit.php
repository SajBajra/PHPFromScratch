<?php

require __DIR__ . '/helpers.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';
$message = $_POST['message'] ?? '';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $errors[] = 'Please submit the form from the contact page.';
} else {
    if (!csrf_verify()) {
        $errors[] = 'Invalid security token. Please resubmit the form.';
    } else {
        if (trim($name) === '') {
            $errors[] = 'Name is required.';
        }

        if (trim($email) === '') {
            $errors[] = 'Email is required.';
        } elseif (!validate_email($email)) {
            $errors[] = 'Email is not valid.';
        }

        if (trim($message) === '') {
            $errors[] = 'Message is required.';
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Contact form result</title>
</head>
<body>
    <h1>Contact form result</h1>

    <?php if (!empty($errors)): ?>
        <ul style="color: red;">
            <?php foreach ($errors as $error): ?>
                <li><?php echo htmlspecialchars($error, ENT_QUOTES); ?></li>
            <?php endforeach; ?>
        </ul>

        <p><a href="08-contact-form.php">Go back to the contact form</a></p>
    <?php else: ?>
        <p>Thank you, <?php echo htmlspecialchars($name, ENT_QUOTES); ?>. We received your message.</p>

        <h2>Summary</h2>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($email, ENT_QUOTES); ?></p>
        <p><strong>Message:</strong></p>
        <p><?php echo nl2br(htmlspecialchars($message, ENT_QUOTES)); ?></p>

        <p><a href="08-contact-form.php">Send another message</a></p>
    <?php endif; ?>
</body>
</html>


<?php

$message = $_POST['message'] ?? '';
$info = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $trimmed = trim($message);

    if ($trimmed === '') {
        $info = 'Please enter a message before saving.';
    } else {
        $line = $trimmed . PHP_EOL;
        $filePath = __DIR__ . '/../data/messages.txt';

        if (!is_dir(dirname($filePath))) {
            mkdir(dirname($filePath), 0777, true);
        }

        $result = file_put_contents($filePath, $line, FILE_APPEND | LOCK_EX);

        if ($result === false) {
            $info = 'There was a problem writing to the file.';
        } else {
            $info = 'Message saved to file.';
            $message = '';
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Write to a file</title>
</head>
<body>
    <h1>Write to a file</h1>

    <?php if ($info !== ''): ?>
        <p><?php echo htmlspecialchars($info, ENT_QUOTES); ?></p>
    <?php endif; ?>

    <form method="post" action="">
        <label>
            Message to save:
            <br>
            <textarea name="message" rows="4" cols="40"><?php echo htmlspecialchars($message, ENT_QUOTES); ?></textarea>
        </label>
        <br>
        <button type="submit">Save message</button>
    </form>

    <p><a href="14-file-read.php">View saved messages</a></p>
</body>
</html>


<?php

$filePath = __DIR__ . '/../data/guestbook.txt';
$message = $_POST['message'] ?? '';
$info = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $trimmed = trim($message);

    if ($trimmed === '') {
        $info = 'Please write a message before submitting.';
    } else {
        if (!is_dir(dirname($filePath))) {
            mkdir(dirname($filePath), 0777, true);
        }

        $line = date('Y-m-d H:i:s') . ' - ' . $trimmed . PHP_EOL;
        $result = file_put_contents($filePath, $line, FILE_APPEND | LOCK_EX);

        if ($result === false) {
            $info = 'There was a problem saving your entry.';
        } else {
            $info = 'Your entry was added to the guestbook.';
            $message = '';
        }
    }
}

$entries = [];
if (is_readable($filePath)) {
    $entries = file($filePath, FILE_IGNORE_NEW_LINES);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Simple guestbook</title>
</head>
<body>
    <h1>Simple guestbook</h1>

    <?php if ($info !== ''): ?>
        <p><?php echo htmlspecialchars($info, ENT_QUOTES); ?></p>
    <?php endif; ?>

    <form method="post" action="">
        <label>
            Your message:
            <br>
            <textarea name="message" rows="4" cols="40"><?php echo htmlspecialchars($message, ENT_QUOTES); ?></textarea>
        </label>
        <br>
        <button type="submit">Add to guestbook</button>
    </form>

    <h2>Previous entries</h2>

    <?php if (empty($entries)): ?>
        <p>No entries yet. Be the first!</p>
    <?php else: ?>
        <ul>
            <?php foreach (array_reverse($entries) as $entry): ?>
                <li><?php echo htmlspecialchars($entry, ENT_QUOTES); ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</body>
</html>


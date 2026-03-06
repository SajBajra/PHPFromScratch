<?php

$filePath = __DIR__ . '/../data/messages.txt';
$lines = [];

if (is_readable($filePath)) {
    $lines = file($filePath, FILE_IGNORE_NEW_LINES);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Read from a file</title>
</head>
<body>
    <h1>Read from a file</h1>

    <?php if (empty($lines)): ?>
        <p>No messages have been saved yet.</p>
    <?php else: ?>
        <ul>
            <?php foreach ($lines as $line): ?>
                <li><?php echo htmlspecialchars($line, ENT_QUOTES); ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <p><a href="13-file-write.php">Back to write form</a></p>
</body>
</html>


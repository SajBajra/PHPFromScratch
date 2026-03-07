<?php

$config = require __DIR__ . '/db-config.php';

try {
    $pdo = new PDO(
        $config['dsn'],
        $config['username'],
        $config['password'],
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
    $message = 'Connected to MySQL successfully.';
} catch (PDOException $e) {
    $message = 'Connection failed: ' . $e->getMessage();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>PDO connection test</title>
</head>
<body>
    <h1>PDO connection test</h1>
    <p><?php echo htmlspecialchars($message, ENT_QUOTES); ?></p>
    <?php if (isset($pdo)): ?>
        <p><a href="17-pdo-basics.php">Next: PDO basics (table, insert, select)</a></p>
    <?php endif; ?>
</body>
</html>

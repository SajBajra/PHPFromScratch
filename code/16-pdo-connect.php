<?php

if (is_file(__DIR__ . '/../vendor/autoload.php')) {
    require __DIR__ . '/../vendor/autoload.php';
} else {
    require __DIR__ . '/Database.php';
}
$config = require __DIR__ . '/db-config.php';

$pdo = null;
try {
    $db = new Database($config);
    $pdo = $db->getPdo();
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

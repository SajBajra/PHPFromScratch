<?php

$message = '';
$trigger = $_GET['trigger'] ?? '';

if ($trigger === 'exception') {
    try {
        throw new RuntimeException('Something went wrong (this is intentional).');
    } catch (RuntimeException $e) {
        $message = 'Caught: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES);
    }
} elseif ($trigger === 'pdo') {
    try {
        $pdo = new PDO('mysql:host=localhost;dbname=nonexistent_db', 'root', '');
    } catch (PDOException $e) {
        $message = 'Connection failed: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES);
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Error handling demo</title>
</head>
<body>
    <h1>Error handling demo</h1>
    <p>Use try/catch to handle exceptions and show friendly messages instead of a raw error.</p>

    <ul>
        <li><a href="?trigger=exception">Trigger a RuntimeException</a></li>
        <li><a href="?trigger=pdo">Trigger a PDOException (bad DB name)</a></li>
        <li><a href="20-error-handling-demo.php">Clear</a></li>
    </ul>

    <?php if ($message !== ''): ?>
        <p style="color: #c00;"><?php echo $message; ?></p>
    <?php endif; ?>
</body>
</html>

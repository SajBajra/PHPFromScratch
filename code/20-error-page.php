<?php

// Simple custom error page. In a real app you might set this via .htaccess
// (ErrorDocument 500 /code/20-error-page.php) or your framework's error handler.

$code = (int) ($_GET['code'] ?? 500);
if ($code !== 404 && $code !== 500) {
    $code = 500;
}

http_response_code($code);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Error <?php echo $code; ?></title>
</head>
<body>
    <h1>Error <?php echo $code; ?></h1>
    <?php if ($code === 404): ?>
        <p>The page you requested was not found.</p>
    <?php else: ?>
        <p>Something went wrong on our side. Please try again later.</p>
    <?php endif; ?>
    <p><a href="18-crud-list.php">Back to items</a></p>
</body>
</html>

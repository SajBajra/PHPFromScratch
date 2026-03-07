<?php
$layoutTitle = $layoutTitle ?? 'PHP From Scratch';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($layoutTitle, ENT_QUOTES); ?></title>
    <style>
        body { font-family: sans-serif; max-width: 800px; margin: 0 auto; padding: 1rem; }
        nav { margin-bottom: 1.5rem; }
        nav a { margin-right: 1rem; }
    </style>
</head>
<body>
    <nav>
        <a href="25-index.php">Home</a>
        <a href="18-crud-list.php">Items</a>
        <a href="22-todo-list.php">Todos</a>
        <a href="27-blog-list.php">Blog</a>
    </nav>

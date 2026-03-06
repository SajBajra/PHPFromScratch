<?php

$theme = $_COOKIE['theme'] ?? 'light';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newTheme = $_POST['theme'] ?? 'light';
    if (in_array($newTheme, ['light', 'dark'], true)) {
        // Remember theme for 30 days.
        setcookie('theme', $newTheme, time() + 30 * 24 * 60 * 60, '/');
        $theme = $newTheme;
    }
}

$isDark = $theme === 'dark';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Theme preference with cookies</title>
    <style>
        body {
            font-family: sans-serif;
            background-color: <?php echo $isDark ? '#121212' : '#ffffff'; ?>;
            color: <?php echo $isDark ? '#f5f5f5' : '#222222'; ?>;
        }
        .card {
            max-width: 480px;
            margin: 2rem auto;
            padding: 1.5rem;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
            background-color: <?php echo $isDark ? '#1e1e1e' : '#fdfdfd'; ?>;
        }
        button {
            margin-top: 0.75rem;
        }
    </style>
</head>
<body>
    <div class="card">
        <h1>Theme preference with cookies</h1>

        <p>Current theme: <strong><?php echo htmlspecialchars($theme, ENT_QUOTES); ?></strong></p>

        <form method="post" action="">
            <label>
                <input type="radio" name="theme" value="light" <?php echo $theme === 'light' ? 'checked' : ''; ?>>
                Light
            </label>
            <br>
            <label>
                <input type="radio" name="theme" value="dark" <?php echo $theme === 'dark' ? 'checked' : ''; ?>>
                Dark
            </label>
            <br>
            <button type="submit">Save theme</button>
        </form>
    </div>
</body>
</html>


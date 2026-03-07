<?php

session_start();

// Generate a CSRF token once per session (or regenerate after each use for extra safety).
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$csrfToken = $_SESSION['csrf_token'];
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $submittedToken = $_POST['csrf_token'] ?? '';

    if (!hash_equals($csrfToken, $submittedToken)) {
        $message = 'Invalid or missing CSRF token. Request rejected.';
    } else {
        $message = 'Token valid. Form accepted. (In a real app you would process the form here.)';
        // Optional: regenerate token after successful use.
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CSRF token demo</title>
</head>
<body>
    <h1>CSRF token demo</h1>
    <p>Forms that change data should include a hidden token stored in the session. On submit we check that the token matches; if not, we reject the request. This blocks cross-site request forgery (a site you're logged into can't submit forms on your behalf without the token).</p>

    <?php if ($message !== ''): ?>
        <p style="color: <?php echo strpos($message, 'Invalid') !== false ? '#c00' : '#080'; ?>;"><?php echo htmlspecialchars($message, ENT_QUOTES); ?></p>
    <?php endif; ?>

    <form method="post" action="">
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrfToken, ENT_QUOTES); ?>">
        <label>
            Say something:
            <input type="text" name="comment" value="Hello">
        </label>
        <button type="submit">Submit</button>
    </form>
</body>
</html>

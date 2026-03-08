<?php

require __DIR__ . '/includes/require-auth.php';

$email = $_SESSION['auth_email'] ?? '';

$layoutTitle = 'Dashboard';
require __DIR__ . '/includes/header.php';

?>
    <h1>Dashboard</h1>
    <p>You are logged in as <strong><?php echo htmlspecialchars($email, ENT_QUOTES); ?></strong>.</p>
    <p><a href="26-logout.php">Log out</a></p>
<?php require __DIR__ . '/includes/footer.php'; ?>

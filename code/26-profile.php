<?php

require __DIR__ . '/includes/require-auth.php';
require_once __DIR__ . '/helpers.php';

if (is_file(__DIR__ . '/../vendor/autoload.php')) {
    require __DIR__ . '/../vendor/autoload.php';
} else {
    require __DIR__ . '/Database.php';
}
$config = require __DIR__ . '/db-config.php';

try {
    $db = new Database($config);
    $pdo = $db->getPdo();
} catch (PDOException $e) {
    die('Connection failed: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES));
}

$email = $_SESSION['auth_email'] ?? '';
$userId = (int) ($_SESSION['auth_user_id'] ?? 0);

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify()) {
        $error = 'Invalid security token. Please try again.';
    } else {
        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        if ($currentPassword === '' || $newPassword === '' || $confirmPassword === '') {
            $error = 'All password fields are required.';
        } elseif ($newPassword !== $confirmPassword) {
            $error = 'New password and confirmation do not match.';
        } elseif (strlen($newPassword) < 8) {
            $error = 'New password must be at least 8 characters.';
        } else {
            $stmt = $pdo->prepare('SELECT password_hash FROM users WHERE id = :id');
            $stmt->execute(['id' => $userId]);
            $user = $stmt->fetch();

            if (!$user || !password_verify($currentPassword, $user['password_hash'])) {
                $error = 'Current password is incorrect.';
            } else {
                $newHash = password_hash($newPassword, PASSWORD_DEFAULT);
                $update = $pdo->prepare('UPDATE users SET password_hash = :hash WHERE id = :id');
                $update->execute(['hash' => $newHash, 'id' => $userId]);
                $success = 'Password updated successfully.';
            }
        }
    }
}

$layoutTitle = 'Your profile';
require __DIR__ . '/includes/header.php';

?>
    <h1>Your profile</h1>

    <p>You are logged in as <strong><?php echo htmlspecialchars($email, ENT_QUOTES); ?></strong>.</p>

    <?php if ($success !== ''): ?>
        <p style="color: green;"><?php echo htmlspecialchars($success, ENT_QUOTES); ?></p>
    <?php endif; ?>

    <?php if ($error !== ''): ?>
        <p style="color: red;"><?php echo htmlspecialchars($error, ENT_QUOTES); ?></p>
    <?php endif; ?>

    <h2>Change password</h2>
    <form method="post" action="">
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(csrf_token(), ENT_QUOTES); ?>">
        <div>
            <label>Current password:<br>
                <input type="password" name="current_password" required>
            </label>
        </div>
        <div>
            <label>New password (min 8 chars):<br>
                <input type="password" name="new_password" required minlength="8">
            </label>
        </div>
        <div>
            <label>Confirm new password:<br>
                <input type="password" name="confirm_password" required minlength="8">
            </label>
        </div>
        <button type="submit">Update password</button>
    </form>

    <p><a href="26-dashboard.php">Back to dashboard</a></p>
<?php require __DIR__ . '/includes/footer.php'; ?>


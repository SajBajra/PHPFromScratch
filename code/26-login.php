<?php

session_start();

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

// If already logged in, redirect to dashboard (or original page if remembered).
if (!empty($_SESSION['auth_logged_in']) && $_SESSION['auth_logged_in'] === true) {
    $target = $_SESSION['auth_redirect_to'] ?? '26-dashboard.php';
    unset($_SESSION['auth_redirect_to']);
    header('Location: ' . $target);
    exit;
}

$message = $_SESSION['login_message'] ?? '';
$error = $_SESSION['login_error'] ?? '';
unset($_SESSION['login_message'], $_SESSION['login_error']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $maxAttempts = 5;
    $lockSeconds = 60;
    $failCount = (int) ($_SESSION['login_fail_count'] ?? 0);
    $lastFail  = (int) ($_SESSION['login_last_fail_time'] ?? 0);
    $now = time();

    if ($failCount >= $maxAttempts && ($now - $lastFail) < $lockSeconds) {
        $_SESSION['login_error'] = 'Too many failed login attempts. Please wait a moment and try again.';
        header('Location: 26-login.php');
        exit;
    }

    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === '' || $password === '') {
        $_SESSION['login_error'] = 'Email and password are required.';
        header('Location: 26-login.php');
        exit;
    }

    $stmt = $pdo->prepare("SELECT id, email, password_hash FROM users WHERE email = :email");
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password_hash'])) {
        // Reset failure counter on successful login.
        unset($_SESSION['login_fail_count'], $_SESSION['login_last_fail_time']);
        $_SESSION['auth_logged_in'] = true;
        $_SESSION['auth_user_id'] = (int) $user['id'];
        $_SESSION['auth_email'] = $user['email'];
        $target = $_SESSION['auth_redirect_to'] ?? '26-dashboard.php';
        unset($_SESSION['auth_redirect_to']);
        header('Location: ' . $target);
        exit;
    }

    $_SESSION['login_fail_count'] = $failCount + 1;
    $_SESSION['login_last_fail_time'] = $now;
    $_SESSION['login_error'] = 'Invalid email or password.';
    header('Location: 26-login.php');
    exit;
}

$layoutTitle = 'Log in';
require __DIR__ . '/includes/header.php';

?>
    <h1>Log in</h1>

    <?php if ($message !== ''): ?>
        <p style="color: green;"><?php echo htmlspecialchars($message, ENT_QUOTES); ?></p>
    <?php endif; ?>
    <?php if ($error !== ''): ?>
        <p style="color: red;"><?php echo htmlspecialchars($error, ENT_QUOTES); ?></p>
    <?php endif; ?>

    <form method="post" action="">
        <div>
            <label>
                Email:
                <input type="email" name="email" required>
            </label>
        </div>
        <div>
            <label>
                Password:
                <input type="password" name="password" required>
            </label>
        </div>
        <button type="submit">Log in</button>
    </form>

    <p><a href="26-register.php">Don't have an account? Register</a></p>

<?php require __DIR__ . '/includes/footer.php'; ?>

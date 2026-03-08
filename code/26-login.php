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

// If already logged in, redirect to dashboard
if (!empty($_SESSION['auth_logged_in']) && $_SESSION['auth_logged_in'] === true) {
    header('Location: 26-dashboard.php');
    exit;
}

$message = $_SESSION['login_message'] ?? '';
$error = $_SESSION['login_error'] ?? '';
unset($_SESSION['login_message'], $_SESSION['login_error']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
        $_SESSION['auth_logged_in'] = true;
        $_SESSION['auth_user_id'] = (int) $user['id'];
        $_SESSION['auth_email'] = $user['email'];
        header('Location: 26-dashboard.php');
        exit;
    }

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

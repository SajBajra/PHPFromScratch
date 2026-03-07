<?php

session_start();

$config = require __DIR__ . '/db-config.php';

try {
    $pdo = new PDO(
        $config['dsn'],
        $config['username'],
        $config['password'],
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
} catch (PDOException $e) {
    die('Connection failed: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES));
}

$pdo->exec("
    CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        email VARCHAR(255) NOT NULL UNIQUE,
        password_hash VARCHAR(255) NOT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )
");

$email = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $passwordConfirm = $_POST['password_confirm'] ?? '';

    if ($email === '') {
        $error = 'Email is required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Email is not valid.';
    } elseif ($password === '') {
        $error = 'Password is required.';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters.';
    } elseif ($password !== $passwordConfirm) {
        $error = 'Passwords do not match.';
    } else {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        if ($stmt->fetch()) {
            $error = 'An account with that email already exists.';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $insert = $pdo->prepare("INSERT INTO users (email, password_hash) VALUES (:email, :hash)");
            $insert->execute(['email' => $email, 'hash' => $hash]);
            $_SESSION['login_error'] = 'Account created. You can log in now.';
            header('Location: 26-login.php');
            exit;
        }
    }
}

$layoutTitle = 'Register';
require __DIR__ . '/includes/header.php';

?>
    <h1>Register</h1>

    <?php if ($error !== ''): ?>
        <p style="color: red;"><?php echo htmlspecialchars($error, ENT_QUOTES); ?></p>
    <?php endif; ?>

    <form method="post" action="">
        <div>
            <label>
                Email:
                <input type="email" name="email" value="<?php echo htmlspecialchars($email, ENT_QUOTES); ?>" required>
            </label>
        </div>
        <div>
            <label>
                Password:
                <input type="password" name="password" required>
            </label>
        </div>
        <div>
            <label>
                Confirm password:
                <input type="password" name="password_confirm" required>
            </label>
        </div>
        <button type="submit">Create account</button>
    </form>

    <p><a href="26-login.php">Already have an account? Log in</a></p>

<?php require __DIR__ . '/includes/footer.php'; ?>


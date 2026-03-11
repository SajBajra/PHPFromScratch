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

$title = '';
$body = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify()) {
        $error = 'Invalid security token. Please try again.';
    } else {
        $title = trim($_POST['title'] ?? '');
        $body = trim($_POST['body'] ?? '');

        if ($title === '') {
            $error = 'Title is required.';
        } elseif (strlen($title) > 255) {
            $error = 'Title must be at most 255 characters.';
        } elseif ($body === '') {
            $error = 'Body is required.';
        } else {
            $stmt = $pdo->prepare("
                INSERT INTO notes_app_notes (user_id, title, body)
                VALUES (:user_id, :title, :body)
            ");
            $stmt->execute([
                'user_id' => (int) ($_SESSION['auth_user_id'] ?? 0),
                'title'   => $title,
                'body'    => $body,
            ]);
            $_SESSION['notes_flash'] = 'Note added.';
            header('Location: 28-notes-list.php');
            exit;
        }
    }
}

$layoutTitle = 'Add note';
require __DIR__ . '/includes/header.php';

?>
    <h1>Add note</h1>

    <?php if ($error !== ''): ?>
        <p style="color: red;"><?php echo htmlspecialchars($error, ENT_QUOTES); ?></p>
    <?php endif; ?>

    <form method="post" action="">
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(csrf_token(), ENT_QUOTES); ?>">
        <div>
            <label>Title:<br>
                <input type="text" name="title" value="<?php echo htmlspecialchars($title, ENT_QUOTES); ?>" maxlength="255" required>
            </label>
        </div>
        <div>
            <label>Body:<br>
                <textarea name="body" rows="8" cols="60"><?php echo htmlspecialchars($body, ENT_QUOTES); ?></textarea>
            </label>
        </div>
        <button type="submit">Save note</button>
    </form>

    <p><a href="28-notes-list.php">Back to notes</a></p>
<?php require __DIR__ . '/includes/footer.php'; ?>


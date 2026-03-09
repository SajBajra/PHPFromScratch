<?php

require __DIR__ . '/includes/require-auth.php';

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

$id = isset($_GET['id']) ? (int) $_GET['id'] : (isset($_POST['id']) ? (int) $_POST['id'] : 0);

if ($id < 1) {
    header('Location: 27-blog-list.php');
    exit;
}

$stmt = $pdo->prepare("SELECT id, title, body FROM posts WHERE id = :id");
$stmt->execute(['id' => $id]);
$post = $stmt->fetch();

if (!$post) {
    header('Location: 27-blog-list.php');
    exit;
}

$title = $post['title'];
$body = $post['body'];
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $body = trim($_POST['body'] ?? '');

    if ($title === '') {
        $error = 'Title is required.';
    } elseif (strlen($title) > 255) {
        $error = 'Title must be at most 255 characters.';
    } elseif ($body === '') {
        $error = 'Body is required.';
    } else {
        $stmt = $pdo->prepare("UPDATE posts SET title = :title, body = :body WHERE id = :id");
        $stmt->execute(['title' => $title, 'body' => $body, 'id' => $id]);
        $_SESSION['blog_flash'] = 'Post updated.';
        header('Location: 27-blog-view.php?id=' . $id);
        exit;
    }
}

$layoutTitle = 'Edit post – Blog';
require __DIR__ . '/includes/header.php';

?>
    <h1>Edit post</h1>

    <?php if ($error !== ''): ?>
        <p style="color: red;"><?php echo htmlspecialchars($error, ENT_QUOTES); ?></p>
    <?php endif; ?>

    <form method="post" action="">
        <input type="hidden" name="id" value="<?php echo (int) $id; ?>">
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
        <button type="submit">Update</button>
    </form>

    <p><a href="27-blog-view.php?id=<?php echo (int) $id; ?>">Back to post</a> · <a href="27-blog-list.php">Back to list</a></p>
<?php require __DIR__ . '/includes/footer.php'; ?>

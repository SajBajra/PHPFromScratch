<?php

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

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id < 1) {
    header('Location: 27-blog-list.php');
    exit;
}

// Ensure comments table exists.
$pdo->exec("
    CREATE TABLE IF NOT EXISTS comments (
        id INT AUTO_INCREMENT PRIMARY KEY,
        post_id INT NOT NULL,
        user_id INT NULL,
        body TEXT NOT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )
");

$commentError = '';
$commentBody = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (empty($_SESSION['auth_logged_in']) || $_SESSION['auth_logged_in'] !== true) {
        $commentError = 'You must be logged in to comment.';
    } elseif (!csrf_verify()) {
        $commentError = 'Invalid security token. Please try again.';
    } else {
        $commentBody = trim($_POST['comment_body'] ?? '');

        if ($commentBody === '') {
            $commentError = 'Comment is required.';
        } else {
            $stmt = $pdo->prepare("
                INSERT INTO comments (post_id, user_id, body)
                VALUES (:post_id, :user_id, :body)
            ");
            $stmt->execute([
                'post_id' => $id,
                'user_id' => (int) ($_SESSION['auth_user_id'] ?? 0) ?: null,
                'body'    => $commentBody,
            ]);
            $_SESSION['comment_flash'] = 'Comment added.';
            header('Location: 27-blog-view.php?id=' . $id);
            exit;
        }
    }
}

$stmt = $pdo->prepare("SELECT id, title, body, created_at, user_id FROM posts WHERE id = :id");
$stmt->execute(['id' => $id]);
$post = $stmt->fetch();

if (!$post) {
    header('Location: 27-blog-list.php');
    exit;
}

$stmt = $pdo->prepare("
    SELECT c.body, c.created_at, u.email
    FROM comments c
    LEFT JOIN users u ON c.user_id = u.id
    WHERE c.post_id = :post_id
    ORDER BY c.created_at ASC, c.id ASC
");
$stmt->execute(['post_id' => $id]);
$comments = $stmt->fetchAll();

$commentFlash = $_SESSION['comment_flash'] ?? '';
unset($_SESSION['comment_flash']);

$layoutTitle = htmlspecialchars($post['title'], ENT_QUOTES) . ' – Blog';
require __DIR__ . '/includes/header.php';

?>
    <h1><?php echo htmlspecialchars($post['title'], ENT_QUOTES); ?></h1>
    <p><small><?php echo htmlspecialchars($post['created_at'], ENT_QUOTES); ?></small></p>
    <div><?php echo nl2br(htmlspecialchars($post['body'], ENT_QUOTES)); ?></div>
    <p>
        <a href="27-blog-list.php">Back to list</a>
        <?php
        $canEdit = !empty($_SESSION['auth_logged_in']) && $_SESSION['auth_logged_in'] === true
            && (empty($post['user_id']) || (int) $post['user_id'] === (int) ($_SESSION['auth_user_id'] ?? 0));
        if ($canEdit): ?>
            · <a href="27-blog-edit.php?id=<?php echo (int) $post['id']; ?>">Edit</a>
            · <a href="27-blog-delete-confirm.php?id=<?php echo (int) $post['id']; ?>">Delete</a>
        <?php endif; ?>
    </p>

    <hr>

    <h2>Comments</h2>

    <?php if ($commentFlash !== ''): ?>
        <p style="color: green;"><?php echo htmlspecialchars($commentFlash, ENT_QUOTES); ?></p>
    <?php endif; ?>

    <?php if ($commentError !== ''): ?>
        <p style="color: red;"><?php echo htmlspecialchars($commentError, ENT_QUOTES); ?></p>
    <?php endif; ?>

    <?php if (empty($comments)): ?>
        <p>No comments yet.</p>
    <?php else: ?>
        <ul>
            <?php foreach ($comments as $comment): ?>
                <li>
                    <p><?php echo nl2br(htmlspecialchars($comment['body'], ENT_QUOTES)); ?></p>
                    <p>
                        <small>
                            <?php echo htmlspecialchars($comment['created_at'], ENT_QUOTES); ?>
                            <?php if (!empty($comment['email'])): ?>
                                – by <?php echo htmlspecialchars($comment['email'], ENT_QUOTES); ?>
                            <?php endif; ?>
                        </small>
                    </p>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <?php if (!empty($_SESSION['auth_logged_in']) && $_SESSION['auth_logged_in'] === true): ?>
        <h3>Add a comment</h3>
        <form method="post" action="">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(csrf_token(), ENT_QUOTES); ?>">
            <div>
                <label>Comment:<br>
                    <textarea name="comment_body" rows="4" cols="60"><?php echo htmlspecialchars($commentBody, ENT_QUOTES); ?></textarea>
                </label>
            </div>
            <button type="submit">Post comment</button>
        </form>
    <?php else: ?>
        <p><a href="26-login.php">Log in</a> to add a comment.</p>
    <?php endif; ?>

<?php require __DIR__ . '/includes/footer.php'; ?>

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

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id < 1) {
    header('Location: 27-blog-list.php');
    exit;
}

$stmt = $pdo->prepare("SELECT id, title FROM posts WHERE id = :id");
$stmt->execute(['id' => $id]);
$post = $stmt->fetch();

if (!$post) {
    header('Location: 27-blog-list.php');
    exit;
}

$layoutTitle = 'Confirm delete – Blog';
require __DIR__ . '/includes/header.php';

?>
    <h1>Confirm delete</h1>
    <p>Are you sure you want to delete <strong><?php echo htmlspecialchars($post['title'], ENT_QUOTES); ?></strong>?</p>
    <form method="post" action="27-blog-delete.php">
        <input type="hidden" name="id" value="<?php echo (int) $post['id']; ?>">
        <button type="submit">Delete</button>
    </form>
    <p><a href="27-blog-view.php?id=<?php echo (int) $id; ?>">Cancel</a></p>
<?php require __DIR__ . '/includes/footer.php'; ?>

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
    CREATE TABLE IF NOT EXISTS posts (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        body TEXT NOT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )
");

$perPage = 5;
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
if ($page < 1) {
    $page = 1;
}

$total = (int) $pdo->query("SELECT COUNT(*) FROM posts")->fetchColumn();
$totalPages = $total > 0 ? (int) ceil($total / $perPage) : 1;
if ($page > $totalPages) {
    $page = $totalPages;
}

$offset = ($page - 1) * $perPage;
$stmt = $pdo->prepare("SELECT id, title, created_at FROM posts ORDER BY created_at DESC LIMIT :limit OFFSET :offset");
$stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$posts = $stmt->fetchAll();

$flash = $_SESSION['blog_flash'] ?? '';
unset($_SESSION['blog_flash']);

?>
<?php $layoutTitle = 'Blog'; require __DIR__ . '/includes/header.php'; ?>

    <h1>Blog</h1>

    <?php if ($flash !== ''): ?>
        <p style="color: green;"><?php echo htmlspecialchars($flash, ENT_QUOTES); ?></p>
    <?php endif; ?>

    <p><a href="27-blog-add.php">Add post</a></p>

    <?php if (empty($posts)): ?>
        <p>No posts yet.</p>
    <?php else: ?>
        <ul>
            <?php foreach ($posts as $post): ?>
                <li>
                    <a href="27-blog-view.php?id=<?php echo (int) $post['id']; ?>"><?php echo htmlspecialchars($post['title'], ENT_QUOTES); ?></a>
                    <small><?php echo htmlspecialchars($post['created_at'], ENT_QUOTES); ?></small>
                </li>
            <?php endforeach; ?>
        </ul>

        <?php if ($totalPages > 1): ?>
            <p>
                <?php if ($page > 1): ?>
                    <a href="27-blog-list.php?page=<?php echo $page - 1; ?>">Previous</a>
                <?php endif; ?>
                Page <?php echo $page; ?> of <?php echo $totalPages; ?>
                <?php if ($page < $totalPages): ?>
                    <a href="27-blog-list.php?page=<?php echo $page + 1; ?>">Next</a>
                <?php endif; ?>
            </p>
        <?php endif; ?>
    <?php endif; ?>

<?php require __DIR__ . '/includes/footer.php'; ?>

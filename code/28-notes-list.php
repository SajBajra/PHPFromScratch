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

$pdo->exec("
    CREATE TABLE IF NOT EXISTS notes_app_notes (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        title VARCHAR(255) NOT NULL,
        body TEXT NOT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )
");

$userId = (int) ($_SESSION['auth_user_id'] ?? 0);

$stmt = $pdo->prepare("
    SELECT id, title, created_at
    FROM notes_app_notes
    WHERE user_id = :user_id
    ORDER BY created_at DESC, id DESC
");
$stmt->execute(['user_id' => $userId]);
$notes = $stmt->fetchAll();

$flash = $_SESSION['notes_flash'] ?? '';
unset($_SESSION['notes_flash']);

$layoutTitle = 'My notes';
require __DIR__ . '/includes/header.php';

?>
    <h1>My notes</h1>

    <?php if ($flash !== ''): ?>
        <p style="color: green;"><?php echo htmlspecialchars($flash, ENT_QUOTES); ?></p>
    <?php endif; ?>

    <p><a href="28-notes-add.php">Add note</a></p>

    <?php if (empty($notes)): ?>
        <p>You have no notes yet.</p>
    <?php else: ?>
        <ul>
            <?php foreach ($notes as $note): ?>
                <li>
                    <a href="28-notes-view.php?id=<?php echo (int) $note['id']; ?>">
                        <?php echo htmlspecialchars($note['title'], ENT_QUOTES); ?>
                    </a>
                    <small><?php echo htmlspecialchars($note['created_at'], ENT_QUOTES); ?></small>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

<?php require __DIR__ . '/includes/footer.php'; ?>


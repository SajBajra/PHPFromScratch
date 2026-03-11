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
$userId = (int) ($_SESSION['auth_user_id'] ?? 0);

if ($id < 1) {
    header('Location: 28-notes-list.php');
    exit;
}

$stmt = $pdo->prepare("
    SELECT id, title, body, created_at
    FROM notes_app_notes
    WHERE id = :id AND user_id = :user_id
");
$stmt->execute(['id' => $id, 'user_id' => $userId]);
$note = $stmt->fetch();

if (!$note) {
    header('Location: 28-notes-list.php');
    exit;
}

$layoutTitle = htmlspecialchars($note['title'], ENT_QUOTES) . ' – Notes';
require __DIR__ . '/includes/header.php';

?>
    <h1><?php echo htmlspecialchars($note['title'], ENT_QUOTES); ?></h1>
    <p><small><?php echo htmlspecialchars($note['created_at'], ENT_QUOTES); ?></small></p>
    <div><?php echo nl2br(htmlspecialchars($note['body'], ENT_QUOTES)); ?></div>

    <p>
        <a href="28-notes-list.php">Back to notes</a>
        · <a href="28-notes-edit.php?id=<?php echo (int) $note['id']; ?>">Edit</a>
        · <a href="28-notes-delete-confirm.php?id=<?php echo (int) $note['id']; ?>">Delete</a>
    </p>

<?php require __DIR__ . '/includes/footer.php'; ?>


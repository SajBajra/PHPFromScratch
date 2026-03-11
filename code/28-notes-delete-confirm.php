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

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$userId = (int) ($_SESSION['auth_user_id'] ?? 0);

if ($id < 1) {
    header('Location: 28-notes-list.php');
    exit;
}

$stmt = $pdo->prepare("
    SELECT id, title
    FROM notes_app_notes
    WHERE id = :id AND user_id = :user_id
");
$stmt->execute(['id' => $id, 'user_id' => $userId]);
$note = $stmt->fetch();

if (!$note) {
    header('Location: 28-notes-list.php');
    exit;
}

$layoutTitle = 'Confirm delete – Notes';
require __DIR__ . '/includes/header.php';

?>
    <h1>Confirm delete</h1>
    <p>Are you sure you want to delete <strong><?php echo htmlspecialchars($note['title'], ENT_QUOTES); ?></strong>?</p>
    <form method="post" action="28-notes-delete.php">
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(csrf_token(), ENT_QUOTES); ?>">
        <input type="hidden" name="id" value="<?php echo (int) $note['id']; ?>">
        <button type="submit">Delete</button>
    </form>
    <p><a href="28-notes-view.php?id=<?php echo (int) $id; ?>">Cancel</a></p>

<?php require __DIR__ . '/includes/footer.php'; ?>


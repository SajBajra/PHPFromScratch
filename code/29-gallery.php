<?php

require_once __DIR__ . '/helpers.php';

$uploadDir = __DIR__ . '/../uploads/';
$webPathPrefix = '../uploads/';
$message = '';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$authLoggedIn = !empty($_SESSION['auth_logged_in']) && $_SESSION['auth_logged_in'] === true;

// Flash message from delete
if (!empty($_SESSION['gallery_flash'])) {
    $message = $_SESSION['gallery_flash'];
    unset($_SESSION['gallery_flash']);
}

$files = [];
if (is_dir($uploadDir)) {
    foreach (scandir($uploadDir) as $f) {
        if ($f === '.' || $f === '..') {
            continue;
        }
        $ext = strtolower(pathinfo($f, PATHINFO_EXTENSION));
        if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'], true)) {
            $files[] = $f;
        }
    }
}

$layoutTitle = 'Image gallery';
require __DIR__ . '/includes/header.php';

?>
    <h1>Image gallery</h1>

    <p>This gallery shows image files from the <code>uploads/</code> folder (from the file upload demo).</p>

    <?php if ($message !== ''): ?>
        <p style="color: green;"><?php echo htmlspecialchars($message, ENT_QUOTES); ?></p>
    <?php endif; ?>

    <?php if (empty($files)): ?>
        <p>No images uploaded yet. Try the file upload demo first.</p>
    <?php else: ?>
        <div style="display: flex; flex-wrap: wrap; gap: 1rem;">
            <?php foreach ($files as $f): ?>
                <div style="border: 1px solid #ccc; padding: 0.5rem; width: 180px; text-align: center;">
                    <div style="height: 120px; display: flex; align-items: center; justify-content: center; background: #f8f8f8;">
                        <img src="<?php echo htmlspecialchars($webPathPrefix . $f, ENT_QUOTES); ?>"
                             alt="<?php echo htmlspecialchars($f, ENT_QUOTES); ?>"
                             style="max-width: 160px; max-height: 110px;">
                    </div>
                    <div style="margin-top: 0.5rem; font-size: 0.9rem;">
                        <?php echo htmlspecialchars($f, ENT_QUOTES); ?>
                    </div>
                    <?php if ($authLoggedIn): ?>
                        <form method="post" action="29-gallery-delete.php" style="margin-top: 0.5rem;">
                            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(csrf_token(), ENT_QUOTES); ?>">
                            <input type="hidden" name="filename" value="<?php echo htmlspecialchars($f, ENT_QUOTES); ?>">
                            <button type="submit">Delete</button>
                        </form>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

<?php require __DIR__ . '/includes/footer.php'; ?>


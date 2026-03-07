<?php

$uploadDir = __DIR__ . '/../uploads/';
$maxSize = 2 * 1024 * 1024; // 2 MB
$allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'pdf'];
$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_FILES['file']['name'])) {
    $file = $_FILES['file'];

    if ($file['error'] !== UPLOAD_ERR_OK) {
        $error = 'Upload failed (error code ' . $file['error'] . ').';
    } elseif ($file['size'] > $maxSize) {
        $error = 'File is too large. Max ' . ($maxSize / 1024 / 1024) . ' MB.';
    } else {
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $allowedExtensions, true)) {
            $error = 'Allowed types: ' . implode(', ', $allowedExtensions);
        } else {
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            $safeName = bin2hex(random_bytes(8)) . '.' . $ext;
            $path = $uploadDir . $safeName;
            if (move_uploaded_file($file['tmp_name'], $path)) {
                $message = 'File uploaded as ' . htmlspecialchars($safeName, ENT_QUOTES);
            } else {
                $error = 'Could not save file.';
            }
        }
    }
}

$files = [];
if (is_dir($uploadDir)) {
    foreach (scandir($uploadDir) as $f) {
        if ($f === '.' || $f === '..') continue;
        $files[] = $f;
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>File upload demo</title>
</head>
<body>
    <h1>File upload demo</h1>
    <p>Allowed: <?php echo htmlspecialchars(implode(', ', $allowedExtensions), ENT_QUOTES); ?>. Max <?php echo (int)($maxSize / 1024 / 1024); ?> MB.</p>

    <?php if ($message !== ''): ?>
        <p style="color: green;"><?php echo $message; ?></p>
    <?php endif; ?>
    <?php if ($error !== ''): ?>
        <p style="color: red;"><?php echo htmlspecialchars($error, ENT_QUOTES); ?></p>
    <?php endif; ?>

    <form method="post" action="" enctype="multipart/form-data">
        <label>
            Choose file:
            <input type="file" name="file" accept=".jpg,.jpeg,.png,.gif,.pdf">
        </label>
        <button type="submit">Upload</button>
    </form>

    <h2>Uploaded files</h2>
    <?php if (empty($files)): ?>
        <p>None yet.</p>
    <?php else: ?>
        <ul>
            <?php foreach ($files as $f): ?>
                <li><a href="23-file-download.php?f=<?php echo urlencode($f); ?>"><?php echo htmlspecialchars($f, ENT_QUOTES); ?></a></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</body>
</html>

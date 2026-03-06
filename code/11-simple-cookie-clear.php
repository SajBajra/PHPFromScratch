<?php

// To delete a cookie, set its expiration time in the past.
setcookie('name', '', time() - 3600, '/');

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Cookie cleared</title>
</head>
<body>
    <h1>Cookie cleared</h1>

    <p>The "name" cookie has been cleared (or will be cleared on the next request).</p>
    <p><a href="11-simple-cookie.php">Back to the cookie example</a></p>
</body>
</html>


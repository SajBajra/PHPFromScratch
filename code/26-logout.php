<?php

session_start();

unset($_SESSION['auth_logged_in'], $_SESSION['auth_user_id'], $_SESSION['auth_email']);

header('Location: 26-login.php');
exit;

<?php

/**
 * Return true if the string looks like a valid email address.
 */
function validate_email(string $email): bool
{
    return filter_var(trim($email), FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Ensure session and CSRF token exist; return the token for use in forms.
 */
function csrf_token(): string
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Return true if the request has a valid CSRF token in POST.
 */
function csrf_verify(): bool
{
    return isset($_POST['csrf_token']) && hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token']);
}

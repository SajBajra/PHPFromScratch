<?php

/**
 * Return true if the string looks like a valid email address.
 */
function validate_email(string $email): bool
{
    return filter_var(trim($email), FILTER_VALIDATE_EMAIL) !== false;
}

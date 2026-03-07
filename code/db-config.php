<?php

/**
 * Database configuration for PDO (MySQL).
 * Adjust for your environment (Laragon: root, empty password by default).
 * Create a database named php_learn in Laragon/phpMyAdmin if it doesn't exist.
 */

return [
    'dsn'      => 'mysql:host=localhost;dbname=php_learn;charset=utf8mb4',
    'username' => 'root',
    'password' => '',
];

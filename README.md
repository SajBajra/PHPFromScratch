# PHP From Scratch

## About this repo

This repository tracks my journey learning PHP from scratch. Each step is committed with clear messages, including both practice code and notes.

## Tech & prerequisites

- PHP installed and available in the terminal (`php -v`).
- Git installed and configured.
- For PDO examples: MySQL (e.g. via Laragon) and a database named `php_learn` (create it in phpMyAdmin or MySQL CLI).
- Optional: [Composer](https://getcomposer.org/) – run `composer install` from the project root to enable autoloading and install PHPUnit; then run `vendor/bin/phpunit tests` to run tests.

### How to run scripts

From the project root:

```bash
php code/01-hello-world.php
php code/02-variables-and-types.php
php code/03-conditionals-and-loops.php
php code/04-arrays-and-functions.php
php -S localhost:8000 -t code
# then open http://localhost:8000/05-simple-get-form.php in your browser
```

## Structure

- `code/` – practice scripts for each topic.
- `notes/` – short notes per topic.
- `projects/` – small mini-projects built as I progress.

## Progress

- [x] Environment set up
- [x] Hello World
- [x] Basics: variables and types
- [x] Conditionals and loops
- [x] Arrays and functions
- [x] Working with forms (GET/POST)
- [x] Sessions and cookies
- [x] Simple file I/O and guestbook
- [x] PDO + MySQL
- [x] Mini CRUD project
- [x] Security: password hashing demo
- [x] Error handling (try/catch, custom error page)
- [x] CSRF token demo
- [x] Todo app (list, add, toggle done, delete)
- [x] File upload demo
- [x] Simple JSON API (items endpoint)
- [x] Simple routing (front controller + home page)
- [x] Shared layout (header & footer includes)
- [x] Simple blog (list, view, add, edit, delete post)
- [x] Pagination (blog list)
- [x] Auth with database (register, login, logout)
- [x] Header auth links and protected blog add post
- [x] Basic OOP: Database helper class
- [x] All DB scripts use Database class (CRUD, todo, blog, auth, API)
- [x] Composer and autoload (classmap for code/)
- [x] Auth helper (require-auth include)
- [x] Testing (PHPUnit + validate_email helper)

## Commit conventions

Each learning step is captured in small, focused commits. Commit messages describe what I practiced or learned (for example: `Practice PHP variables, types, and string interpolation`).

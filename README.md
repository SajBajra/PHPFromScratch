# PHP From Scratch

## About this repo

This repository tracks my journey learning PHP from scratch. Each step is committed with clear messages, including both practice code and notes.

## Tech & prerequisites

- PHP installed and available in the terminal (`php -v`).
- Git installed and configured.
- For PDO examples: MySQL (e.g. via Laragon) and a database named `php_learn` (create it in phpMyAdmin or MySQL CLI).

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

## Commit conventions

Each learning step is captured in small, focused commits. Commit messages describe what I practiced or learned (for example: `Practice PHP variables, types, and string interpolation`).

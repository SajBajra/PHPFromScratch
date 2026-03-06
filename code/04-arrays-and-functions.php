<?php

$movies = [
    'The Matrix',
    'Inception',
    'Interstellar',
    'The Dark Knight',
    'Spirited Away',
];

function printMovies(array $movies): void
{
    foreach ($movies as $index => $title) {
        echo ($index + 1) . ". $title" . PHP_EOL;
    }
}

function findMovie(string $title, array $movies): bool
{
    return in_array($title, $movies, true);
}

echo "My favorite movies:" . PHP_EOL;
printMovies($movies);

$searchTitle = 'Inception';

echo PHP_EOL;
echo "Searching for '$searchTitle': " . (findMovie($searchTitle, $movies) ? 'found' : 'not found') . PHP_EOL;


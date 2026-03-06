<?php

// Scalars
$age = 25;
$height = 1.80;
$name = "Saj";
$isProgrammer = true;

echo "Age: $age" . PHP_EOL;
echo "Height: $height" . PHP_EOL;
echo "Name: $name" . PHP_EOL;
echo "Is programmer: " . ($isProgrammer ? 'true' : 'false') . PHP_EOL;

// Array
$languages = ["PHP", "Python", "Java"];

echo "Languages: " . implode(", ", $languages) . PHP_EOL;

// Debug info
var_dump($age, $height, $name, $isProgrammer, $languages);

// Single vs double quotes
echo 'Single quotes: Hello, $name' . PHP_EOL;
echo "Double quotes: Hello, $name" . PHP_EOL;


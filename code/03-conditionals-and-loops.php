<?php

function gradeMessage(int $score): string
{
    if ($score >= 90 && $score <= 100) {
        return 'A';
    } elseif ($score >= 80) {
        return 'B';
    } elseif ($score >= 70) {
        return 'C';
    } elseif ($score >= 60) {
        return 'D';
    }

    return 'F';
}

$scores = [95, 82, 74, 63, 50];

foreach ($scores as $score) {
    echo "Score $score => grade " . gradeMessage($score) . PHP_EOL;
}

echo PHP_EOL . "FizzBuzz from 1 to 20:" . PHP_EOL;

for ($i = 1; $i <= 20; $i++) {
    if ($i % 3 === 0 && $i % 5 === 0) {
        echo "FizzBuzz" . PHP_EOL;
    } elseif ($i % 3 === 0) {
        echo "Fizz" . PHP_EOL;
    } elseif ($i % 5 === 0) {
        echo "Buzz" . PHP_EOL;
    } else {
        echo $i . PHP_EOL;
    }
}


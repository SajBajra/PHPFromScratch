<?php

class Database
{
    private PDO $pdo;

    public function __construct(array $config)
    {
        $this->pdo = new PDO(
            $config['dsn'],
            $config['username'],
            $config['password'],
            [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]
        );
    }

    public function getPdo(): PDO
    {
        return $this->pdo;
    }
}


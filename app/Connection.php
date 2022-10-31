<?php

declare(strict_types=1);

namespace App;

use PDO;

class Connection
{
    protected PDO $connection;

    private static ?Connection $instance = null;

    private function __construct()
    {
        $this->connection = $this->connect();
    }

    /**
     * @return static
     */
    public static function getInstance(): static
    {
        if (is_null(static::$instance)) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    /**
     * @return PDO
     */
    public function getPdo(): PDO
    {
        return $this->connection;
    }

    /**
     * @return PDO
     */
    private function connect(): PDO
    {
        return new PDO(
            $this->getDns(),
            $this->getUsername(),
            $this->getPassword()
        );
    }

    /**
     * @return string
     */
    private function getDns(): string
    {
        return sprintf(
            '%s:host=%s;dbname=%s',
            $_ENV['DB_CONNECTION'],
            $_ENV['DB_HOST'],
            $_ENV['DB_DATABASE']
        );
    }

    /**
     * @return string
     */
    private function getUsername(): string
    {
        return $_ENV['DB_USERNAME'];
    }

    /**
     * @return string
     */
    private function getPassword(): string
    {
        return $_ENV['DB_PASSWORD'];
    }
}

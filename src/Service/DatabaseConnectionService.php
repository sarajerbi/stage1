<?php

// src/Service/DatabaseConnectionService.php

namespace App\Service;

use PDO;
use PDOException;

class DatabaseConnectionService
{
    private string $databaseUrl;

    public function __construct(string $databaseUrl)
    {
        $this->databaseUrl = $databaseUrl;
    }

    public function getConnection(string $databaseName): PDO
    {
        // Parse the DATABASE_URL to extract connection details
        $connectionParams = parse_url($this->databaseUrl);

        $scheme = $connectionParams['scheme'] ?? '';
        $host = $connectionParams['host'] ?? '';
        $port = $connectionParams['port'] ?? '';
        $user = $connectionParams['user'] ?? '';
        $pass = $connectionParams['pass'] ?? '';
        $dbname = ltrim($connectionParams['path'], '/'); // Remove leading slash from path

        $dsn = sprintf('%s:host=%s;port=%s;dbname=%s',
            $scheme,
            $host,
            $port,
            $databaseName
        );

        try {
            $pdo = new PDO($dsn, $user, $pass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Test the connection (optional)
            $pdo->query('SELECT 1'); // Replace with an appropriate test query

            return $pdo;
        } catch (PDOException $e) {
            // Handle connection errors (e.g., log, throw custom exception)
            throw new \RuntimeException('Failed to connect to database: ' . $e->getMessage());
        }
    }
}


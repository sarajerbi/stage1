<?php

// src/Controller/ServiceController.php

namespace App\Controller;

use App\Service\DatabaseConnectionService;
use PDO;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ServiceController extends AbstractController
{
    private DatabaseConnectionService $databaseConnectionService;

    public function __construct(DatabaseConnectionService $databaseConnectionService)
    {
        $this->databaseConnectionService = $databaseConnectionService;
    }

    #[Route('/service/{databaseName}', name: 'service_database_connection')]
    public function connectToDatabase(string $databaseName): Response
    {
        try {
            $pdo = $this->databaseConnectionService->getConnection($databaseName);
            // Use $pdo for database operations (e.g., querying, updating)

            // Example usage:
            $stmt = $pdo->query('SELECT * FROM some_table');
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $this->json($results);
        } catch (\RuntimeException $e) {
            // Handle connection failure
            return $this->json(['error' => 'Failed to connect to database: ' . $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

}

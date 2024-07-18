<?php

// src/Service/DynamicQueryService.php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;


class DynamicQueryService
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function searchWordInAllTables(string $searchWord): array
    {
        $results = [];

        // Get all tables and columns in the 'public' schema
        $tableColumns = $this->getTableColumns();

        // Iterate over each table and column to build and execute queries
        foreach ($tableColumns as $tableColumn) {
            $tableName = $tableColumn['table_name'];
            $columnName = $tableColumn['column_name'];

            // Create a query builder for the current entity (table)
            $queryBuilder = $this->entityManager->getRepository('App\Entity\\' . ucfirst($tableName))->createQueryBuilder('t');
            $queryBuilder->select('t.id, :tableName AS table_name')
                         ->setParameter('tableName', $tableName)
                         ->where($queryBuilder->expr()->like("t.$columnName", ':searchWord'))
                         ->setParameter('searchWord', '%' . $searchWord . '%');

            // Execute the query and fetch results
            $tableResults = $queryBuilder->getQuery()->getResult();

            // Add results to the main results array
            $results = array_merge($results, $tableResults);
        }

        return $results;
    }

    private function getTableColumns(): array
    {
        // Query to fetch all tables and columns in the 'public' schema
        $sql = "
            SELECT table_name, column_name
            FROM information_schema.columns
            WHERE table_schema = 'public' AND data_type IN ('character varying', 'text', 'char')
        ";
    
        // Execute the SQL query using Doctrine DBAL connection
        $stmt = $this->entityManager->getConnection()->executeQuery($sql);
    
        // Initialize an array to store results
        $results = [];
    
        // Iterate over the statement and fetch each row as an associative array
        foreach ($stmt as $row) {
            $results[] = $row;
        }
    
        return $results;
    }
}
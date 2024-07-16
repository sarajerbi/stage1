<?php
// src/Repository/ClientRepository.php

namespace App\Repository;

use App\Entity\Client;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ClientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Client::class);
    }

    /**
     * @param string $searchTerm
     * @return Client[]
     */
    public function searchClients(string $searchTerm): array
    {
        return $this->createQueryBuilder('c')
            ->where('c.code LIKE :searchTerm')
            ->orWhere('c.nom LIKE :searchTerm')
            ->orWhere('c.prenom LIKE :searchTerm')
            ->setParameter('searchTerm', '%' . $searchTerm . '%')
            ->getQuery()
            ->getResult();
    }
}

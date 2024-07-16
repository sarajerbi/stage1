<?php

namespace App\Controller;

use App\Repository\ClientRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{
    #[Route('/search', name: 'search')]
    public function search(Request $request, ClientRepository $clientRepository): Response
    {
        $searchTerm = $request->query->get('q');
        $results = [];

        if ($searchTerm) {
            $results = $clientRepository->searchClients($searchTerm);
        }

        return $this->render('search/index.html.twig', [
            'results' => $results,
            'search_term' => $searchTerm,
        ]);
    }

    #[Route('/autocomplete', name: 'autocomplete')]
    public function autocomplete(Request $request, ClientRepository $clientRepository): JsonResponse
    {
        $searchTerm = $request->query->get('term');
        $results = [];

        if ($searchTerm) {
            $clients = $clientRepository->searchClients($searchTerm);
            foreach ($clients as $client) {
                $results[] = [
                    'id' => $client->getId(),
                    'nom' => $client->getNom(),
                    'prenom' => $client->getPrenom(),
                    'code' => $client->getCode()
                ];
            }
        }

        return new JsonResponse($results);
    }
}

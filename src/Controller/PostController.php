<?php


// src/Controller/PostController.php

namespace App\Controller;

use App\Service\DynamicQueryService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    private DynamicQueryService $dynamicQueryService;

    public function __construct(DynamicQueryService $dynamicQueryService)
    {
        $this->dynamicQueryService = $dynamicQueryService;
    }

    #[Route('/post/{searchWord}', name: 'app_post')]
    public function searchWordInAllTables(string $searchWord): JsonResponse
    {
        $results = $this->dynamicQueryService->searchWordInAllTables($searchWord);

        return $this->json($results);
    }
}

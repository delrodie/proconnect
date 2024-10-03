<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Entity\Categorie;
use App\Service\AllRepositories;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;

#[Route('/api-categorie')]
class ApiCategorieController extends AbstractController
{
    public function __construct(private readonly AllRepositories $allRepositories)
    {
    }

    #[Route('/')]
    public function list(): JsonResponse
    {
        $categories = $this->allRepositories->getAllCategorie();
        return $this->json($categories, 200, [],[
            'groups' => ['categorie.list']
        ]);
    }

    #[Route('/{id}', requirements: ['id' => Requirement::DIGITS])]
    public function show(Categorie $categorie): JsonResponse
    {
        return $this->json($categorie, 200, [],[
            'groups' => ['categorie.show', 'categorie.list']
        ]);
    }
}

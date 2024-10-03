<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Entity\Domaine;
use App\Service\AllRepositories;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;

#[Route('/api-domaine')]
class ApiDomaineController extends AbstractController
{
    public function __construct(private readonly AllRepositories $allRepositories)
    {
    }

    #[Route('/')]
    public function list(): JsonResponse
    {
        $domaines = $this->allRepositories->getAllDomaine();
        return $this->json($domaines, 200, [],[
            'groups' => ['domaine.list']
        ]);
    }

    #[Route('/{id}', requirements: ['id' => Requirement::DIGITS])]
    public function show(Domaine $domaine): JsonResponse
    {
        return $this->json($domaine, 200, [],[
            'groups' => ['domaine.list', 'domaine.show']
        ]);
    }
}

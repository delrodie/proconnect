<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Entity\Partenaire;
use App\Service\AllRepositories;
use App\Service\ApiRepositories;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;

#[Route('/api-partenaire')]
class ApiPartenaireController extends AbstractController
{
    public function __construct(private readonly AllRepositories $allRepositories, private readonly ApiRepositories $apiRepositories)
    {
    }

    #[Route('/')]
    public function list(): JsonResponse
    {
        return $this->json(
            $this->apiRepositories->getListPartenaires(),
            200
        );
    }

    #[Route('/{id}', requirements: ['id' => Requirement::DIGITS], methods: ['GET'])]
    public function show(Partenaire $partenaire): JsonResponse
    {
        return $this->json($partenaire, 200);
    }
}

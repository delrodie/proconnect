<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Entity\Localite;
use App\Service\AllRepositories;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;

#[Route('/api-localite')]
class ApiLocaliteController extends AbstractController
{
    public function __construct(private readonly AllRepositories $allRepositories)
    {
    }

    #[Route('/')]
    public function list(): JsonResponse
    {
        return $this->json(
            $localite = $this->allRepositories->getAllLocalite(),
            200
        );
    }

    #[Route('/{id}', requirements: ['id' => Requirement::DIGITS])]
    public function show(Localite $localite)
    {
        return $this->json($localite, 200);
    }
}

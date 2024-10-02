<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Entity\Competence;
use App\Service\AllRepositories;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;

#[Route('/api/competence')]
class ApiCompetenceController extends AbstractController
{
    public function __construct(private readonly AllRepositories $allRepositories)
    {
    }

    #[Route('/')]
    public function list(): JsonResponse
    {
        $competences = $this->allRepositories->getAllCompetence();
        return $this->json($competences, 200, [], []);
    }

    #[Route('/{id}', requirements: ['id' => Requirement::DIGITS])]
    public function show(Competence $competence): JsonResponse
    {
        return $this->json($competence, 200);
    }
}

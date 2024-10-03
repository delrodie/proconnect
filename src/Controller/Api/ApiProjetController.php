<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Service\ApiRepositories;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api-projet')]
class ApiProjetController extends AbstractController
{
    public function __construct(private readonly ApiRepositories $apiRepositories)
    {
    }

    #[Route('/')]
    public function list(): JsonResponse
    {
        return $this->json(
            $this->apiRepositories->getListProjet(),
            200, [],
            ['groups' => 'projet.list']
        );
    }
}

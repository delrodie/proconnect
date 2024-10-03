<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Service\AllRepositories;
use App\Service\ApiRepositories;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/prestataire')]
class ApiPrestataireController extends AbstractController
{
    public function __construct(private readonly ApiRepositories $apiRepositories, private readonly AllRepositories $allRepositories)
    {
    }

    #[Route('/')]
    public function list(): JsonResponse
    {
        return $this->json(
            $this->apiRepositories->getListPrestataire(),
            200, [],
            ['groups' => 'prestataire.list']
        );

    }

    #[Route('/{matricule}', methods: ['GET'])]
    public function show($matricule)
    {
        $prestataire = $this->allRepositories->getOnePrestataire($matricule); //dd($prestataire);
        if (!$prestataire){
            throw new NotFoundHttpException("Le prestataire recherché n'a pas été trouvé.");
        }

        return $this->json(
            $this->apiRepositories->showPrestataire($prestataire),
            200, [],
            ['groups' => 'prestataire.list', 'prestataire.show']
        );
    }
}

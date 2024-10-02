<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Entity\Demandeur;
use App\Service\AllRepositories;
use App\Service\ApiRepositories;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/demandeur')]
class ApiDemandeurController extends AbstractController
{
    public function __construct(private readonly AllRepositories $allRepositories, private readonly ApiRepositories $apiRepositories)
    {
    }

    #[Route('/')]
    public function list(): JsonResponse
    {
        return $this->json(
            $this->apiRepositories->getListDemandeur(),200, [], [
                'groups' => ['demandeur.list']
            ]
        );
    }

    #[Route('/{code}', methods: ['GET'])]
    public function show($code): JsonResponse
    {
        $demandeur = $this->allRepositories->getOneDemandeur($code);
        if (!$demandeur){
            throw new NotFoundHttpException("Le demandeur recherché n'a pas été trouvé");
        }

        return $this->json(
            $this->apiRepositories->showDemandeur($demandeur), 200, [], [
            'groups' => ['demandeur.list', 'demandeur.show']
        ]);
    }

    #[Route('/{code}/messages', methods: ['GET'])]
    public function message($code): JsonResponse
    {
        $demandeur = $this->allRepositories->getOneDemandeur($code);
        if (!$demandeur){
            throw new NotFoundHttpException("Le demandeur recherché n'a pas été trouvé");
        }

        return $this->json($this->apiRepositories->getMessagesByDemandeur($demandeur),
            200, [],[
            'groups' => ['message.show', 'demandeur.list']
        ]);
    }
}

<?php

declare(strict_types=1);

namespace App\Controller\Frontend;

use App\Service\AllRepositories;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/demandeur-{demandeur}')]
class DemandeurProjetController extends AbstractController
{
    public function __construct(
        private AllRepositories $allRepositories
    )
    {
    }

    #[Route('/', name: 'app_frontend_demandeurprojet_index')]
    public function index(): Response
    {
        $verifDemandeur = $this->allRepositories->getOneDemandeur(null, $this->getUser());
        return $this->render('frontend/demandeur_projets.html.twig', [
            'demandeur' => $verifDemandeur
        ]);
    }

    #[Route('/ajout', name: 'app_frontend_demandeurprojet_ajout', methods: ['GET','POST'])]
    public function ajout(Request $request)
    {
        return $this->render('frontend/demandeur_projet_add.html.twig',[
            'demandeur' => $this->allRepositories->getOneDemandeur(null, $this->getUser()),
        ]);
    }

    #[ROute('/{reference}', name: 'app_frontend_demandeurprojet_details', methods: ['GET'])]
    public function details()
    {
        return $this->render('frontend/demandeur_projet_details.html.twig',[
            'demandeur' => $this->allRepositories->getOneDemandeur(null, $this->getUser())
        ]);
    }
}

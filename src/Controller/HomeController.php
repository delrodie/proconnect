<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\AllRepositories;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    public function __construct(
        private AllRepositories $allRepositories
    )
    {
    }

    #[Route('/')]
    public function index(): Response
    {
        if ($this->allRepositories->isMaintenance())
            return $this->redirectToRoute('app_maintenance');

        return $this->render('frontend/home.html.twig',[
            'slide' => $this->allRepositories->getOneSlide(),
            'partenaires' => $this->allRepositories->getAllPartenaire(),
            'action_demandeur' => $this->allRepositories->getOneCallToAction('DEMANDEUR'),
            'action_prestataire' => $this->allRepositories->getOneCallToAction('PRESTATAIRE'),
            'domaines' => $this->allRepositories->getAllDomaine('ASC'),
            'parallax' => $this->allRepositories->getOneParallax(),
            'projets' => $this->allRepositories->findAllProjetByStatut("APPEL")
        ]);
    }
}

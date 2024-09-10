<?php

declare(strict_types=1);

namespace App\Controller\Frontend;

use App\Service\AllRepositories;
use App\Service\Utilities;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/prestataire-{prestataire}')]
class PrestataireProjetController extends AbstractController
{
    public function __construct(
        private AllRepositories $allRepositories,
        private Utilities $utilities
    )
    {
    }

    #[Route('/', name: "app_frontend_prestataire_projet_list")]
    public function index(): Response
    {
        $prestataire = $this->allRepositories->getOnePrestataire(null, $this->getUser());
        return $this->render('frontend_prestataire/projet_list.html.twig',[
            'prestataire' => $prestataire,
            'soumissions' => $this->allRepositories->findPostulerByUser($this->getUser())
        ]);
    }
}

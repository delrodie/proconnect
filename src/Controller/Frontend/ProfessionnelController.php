<?php

declare(strict_types=1);

namespace App\Controller\Frontend;

use App\Service\AllRepositories;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/professionnel')]
class ProfessionnelController extends AbstractController
{
    public function __construct(
        private AllRepositories $allRepositories
    )
    {
    }

    #[Route('/', name: 'app_frontend_professionnel_list')]
    public function list(): Response
    {
        return $this->render('frontend/professionnel_list.html.twig',[
            'prestataires' => $this->allRepositories->getAllPrestataire()
        ]);
    }

    #[Route('/{matricule}', name: 'app_frontend_professionnel_show', methods: ['GET'])]
    #[isGranted('ROLE_USER')]
    public function show($matricule)
    {
        $prestataire = $this->allRepositories->getPrestataireByMatricule($matricule);
        return $this->render('frontend/professionnel_show.html.twig',[
            'prestataire' => $prestataire,
            'candidature' => $this->allRepositories->findPostulerByUser($prestataire->getUser()),
            'projets' => []
        ]);
    }
}

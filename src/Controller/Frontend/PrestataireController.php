<?php

declare(strict_types=1);

namespace App\Controller\Frontend;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/prestataire')]
class PrestataireController extends AbstractController
{
    #[Route('/', name: 'app_frontend_prestataire_tbord')]
    public function index(): Response
    {
        return $this->render('frontend_prestataire/tableau_bord.html.twig',[
            'prestataire' => ['matricule'=>0]
        ]);
    }
}

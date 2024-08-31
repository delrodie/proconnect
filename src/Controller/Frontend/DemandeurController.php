<?php

declare(strict_types=1);

namespace App\Controller\Frontend;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/demandeur')]
class DemandeurController extends AbstractController
{
    #[Route('/', name: 'app_frontend_demandeur_tbord')]
    public function tbord(): Response
    {
        return $this->render('frontend/demandeur_tboard.html.twig');
    }

    #[Route('/profile', name: 'app_frontend_demandeur_profile', methods: ['GET', 'POST'])]
    public function profile(Request $request)
    {
        return $this->render('frontend/demandeur_profile.html.twig');
    }
}

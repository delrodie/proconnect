<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/maintenance')]
class MaintenanceController extends AbstractController
{
    #[Route('/', name: 'app_maintenance')]
    public function index(): Response
    {
        return $this->render('frontend/maintenance.html.twig');
    }

    #[Route('/home', name: 'app_maintenance_home')]
    public function home(): Response
    {
        return $this->render('frontend/home.html.twig');
    }
}

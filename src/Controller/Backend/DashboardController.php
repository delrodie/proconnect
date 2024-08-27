<?php

declare(strict_types=1);

namespace App\Controller\Backend;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/dashboard')]
class DashboardController extends AbstractController
{
    #[Route('/', name: 'app_backend_dashboard')]
    public function index(): Response
    {
        return $this->render('backend/dashboard.html.twig');
    }
}

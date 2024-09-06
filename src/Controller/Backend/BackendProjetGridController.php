<?php

declare(strict_types=1);

namespace App\Controller\Backend;

use App\Repository\ProjetRepository;
use App\Service\AllRepositories;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/backend/projet-grid')]
class BackendProjetGridController extends AbstractController
{
    public function __construct(
        private AllRepositories $allRepositories
    )
    {
    }

    #[Route('/', name: 'app_backend_projetgrid_index')]
    public function index(ProjetRepository $projetRepository): Response
    {
        return $this->render('backend_projet/grid.html.twig', [
            'projets' => $this->allRepositories->findAllProjets(),
        ]);
    }
}

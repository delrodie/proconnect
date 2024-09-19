<?php

declare(strict_types=1);

namespace App\Controller\Frontend;

use App\Service\AllRepositories;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/filtre')]
class FiltreController extends AbstractController
{
    public function __construct(
        private AllRepositories $allRepositories
    )
    {
    }

    #[Route('/', name: 'app_frontend_filtre_box')]
    public function box(): Response
    {
        return $this->render('frontend/filtre_box.html.twig',[
            'domaines' => $this->allRepositories->getAllDomaine(),
            'localites' => $this->allRepositories->getAllLocalite()
        ]);
    }
}

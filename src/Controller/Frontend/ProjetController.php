<?php

declare(strict_types=1);

namespace App\Controller\Frontend;

use App\Service\AllRepositories;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/projet')]
class ProjetController extends AbstractController
{
    public function __construct(
        private AllRepositories $allRepositories
    )
    {
    }

    #[Route('/', name: 'app_frontend_projet_list', methods: ['GET'])]
    public function index(Request $request): Response
    {
        $filter = $request->get('filter');
        $projets = match ($filter){
            'BUDGET' => $this->allRepositories->findAllProjetByStatut("APPEL", null, $filter),
            'DATE' => $this->allRepositories->findAllProjetByStatut("APPEL", $filter),
            default => $this->allRepositories->findAllProjetByStatut("APPEL"),
        };
        return $this->render('frontend_projet/list.html.twig',[
            'projets' =>$projets
        ]);
    }

    #[Route('/{reference}', name: 'app_frontend_projet_show', methods: ['GET'])]
    public function show($reference)
    {

        return $this->render('frontend_projet/show.html.twig',[
            'projet' => $this->allRepositories->getOneProjet($reference)
        ]);
    }

    #[Route('/{reference}/postuler', name: 'app_frontend_projet_postuler',methods: ['GET','POST'])]
    public function postuler(Request $request, $reference): Response
    {
        $projet = $this->allRepositories->getOneProjet($reference);
        return $this->render('frontend_demandeur/postuler.html.twig',[
            'projet' =>$projet
        ]);
    }
}

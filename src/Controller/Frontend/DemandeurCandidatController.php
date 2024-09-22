<?php

declare(strict_types=1);

namespace App\Controller\Frontend;

use App\Entity\Postuler;
use App\Service\AllRepositories;
use App\Service\Utilities;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/{demandeur}/mes-prestataires')]
#[isGranted('ROLE_DEMANDEUR')]
class DemandeurCandidatController extends AbstractController
{
    public function __construct(
        private RequestStack $requestStack,
        private AllRepositories $allRepositories,
        private EntityManagerInterface $entityManager,
        private Utilities $utilities
    )
    {
    }

    #[Route('/', name: 'app_frontend_demandeur_candidat_list')]
    public function list(): Response
    {
        return $this->render('frontend_demandeur/candidat_list.html.twig');
    }

    #[Route('/{offre}', name: 'app_frontend_demandeur_candidat_offre', methods: ['GET','POST'])]
    public function offre(Request $request, $offre, $demandeur): Response
    {
        $postuler = $this->allRepositories->getOnePostuler($offre); //dd($request->get('_token'));

        if ($this->isCsrfTokenValid('embauche'.$postuler->getReference(), $request->get('_token'))){
            $postuler->setStatut('EMBAUCHE');
            $postuler->setEmbaucheAt($this->utilities->fuseauGMT());
            $postuler->getProjet()->setStatut('ENCOURS'); //dd($postuler);
            //dd($postuler->getProjet());

            $this->entityManager->flush();

            notyf()->success("Félicitations vous venez d'embaucher ce prestataire sur votre projet. Il vous contactéra sous peu");

            return $this->redirectToRoute('app_frontend_demandeur_projet_details',[
                'reference' => $postuler->getProjet()->getReference(),
                'demandeur' => $demandeur
            ], Response::HTTP_SEE_OTHER);
        }

        return $this->render('frontend_demandeur/candidat_offre.html.twig',[
            'offre' => $postuler,
            'prestataire' => $this->allRepositories->getOnePrestataire(null, $postuler->getUser()),
            'demandeur' => $this->allRepositories->getOneDemandeur($demandeur)
        ]);
    }

    #[Route('/{offre}/embaucher', name: 'app_frontend_demandeur_candidat_embaucher', methods: ['GET'])]
    public function embaucher($offre, $demandeur): Response
    {
        $postuler = $this->allRepositories->getOnePostuler($offre);

        return $this->render('frontend_demandeur/candidat_embauche.html.twig',[
            'offre' => $postuler,
            'demander' => $this->allRepositories->getOneDemandeur($demandeur),
            'prestataire' => $this->allRepositories->getOnePrestataire(null, $postuler->getUser())
        ]);
    }
}

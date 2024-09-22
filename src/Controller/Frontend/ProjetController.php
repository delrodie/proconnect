<?php

declare(strict_types=1);

namespace App\Controller\Frontend;

use App\Entity\Postuler;
use App\Form\PostulerFormType;
use App\Service\AllRepositories;
use App\Service\Utilities;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/projet')]
class ProjetController extends AbstractController
{
    public function __construct(
        private AllRepositories $allRepositories,
        private EntityManagerInterface $entityManager,
        private Utilities $utilities
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
    #[isGranted('ROLE_USER')]
    public function show($reference): Response
    {
        $projet = $this->allRepositories->getProjetDetails($reference);

        return $this->render('frontend_projet/show.html.twig',[
            'projet' => $projet,
            'candidatures' => $this->allRepositories->findCanditatureByProjet($reference),
            'similaires' => $this->allRepositories->findProjetSimilaireByCategorie($projet->getCategorie())
        ]);
    }

    #[Route('/{reference}/postuler', name: 'app_frontend_projet_postuler',methods: ['GET','POST'])]
    #[isGranted('ROLE_USER', message: 'Vous n\'êtes pas autorisé à accéder au formulaire de soumission d\'offre. Cette section est réservée exclusivement aux prestataires.')]
    public function postuler(Request $request, $reference): Response
    {
        $projet = $this->allRepositories->getOneProjet($reference);

        $postuler = new Postuler();
        $form = $this->createForm(PostulerFormType::class, $postuler);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            // Soumission exclusive aux prestataires
            if (!$this->isGranted('ROLE_PRESTATAIRE')){
                sweetalert()->error($this->utilities->messageAlert(401), [], "ACCES NON AUTORISE");

                return $this->redirectToRoute('app_frontend_projet_show',['reference' => $projet->getReference()]);
            }

            // Verifions si l'utilisateur n'a pas encore postulé
            $verif = $this->allRepositories->findPostulerByReferenceAndUser($projet, $this->getUser());
            if ($verif){
                sweetalert()->warning($this->utilities->messageAlert(201), [], "ATTENTION!");

                return $this->redirectToRoute('app_frontend_projet_list');
            }

            $postuler->setReference($this->utilities->referencePostuler());
            $postuler->setUser($this->getUser());
            $postuler->setProjet($projet);
            $postuler->setCreatedAt($this->utilities->fuseauGMT());
            $postuler->setStatut('SOUMIS');

            $this->entityManager->persist($postuler);
            $this->entityManager->flush();

            notyf()->success("Félicitation, votre offre a été envoyée avec succès!",[],"FELICITATIONS");

            return $this->redirectToRoute('app_frontend_projet_list');
        }
        return $this->render('frontend_projet/postuler.html.twig',[
            'projet' =>$projet,
            'postuler' => $postuler,
            'form' => $form
        ]);
    }
}

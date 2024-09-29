<?php

declare(strict_types=1);

namespace App\Controller\Frontend;

use App\Entity\Projet;
use App\Entity\ProjetImage;
use App\Form\ProjetFormType;
use App\Service\AllRepositories;
use App\Service\GestionMedia;
use App\Service\Messages;
use App\Service\Utilities;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/demandeur-{demandeur}')]
#[isGranted('ROLE_DEMANDEUR', message: "Cette session n'est autorisée qu'aux demandeurs de prestations")]
class DemandeurProjetController extends AbstractController
{
    public function __construct(
        private AllRepositories $allRepositories,
        private EntityManagerInterface $entityManager,
        private Utilities $utilities,
        private GestionMedia $gestionMedia
    )
    {
    }

    #[Route('/', name: 'app_frontend_demandeur_projet_index')]
    public function index(): Response
    {
//        $verifDemandeur = $this->allRepositories->getOneDemandeur(null, $this->getUser());
        return $this->render('frontend_demandeur/projets.html.twig', [
            'demandeur' => $this->demandeur(),
            'projets' => $this->allRepositories->findProjetsByUser($this->getUser())
        ]);
    }

    #[Route('/ajout', name: 'app_frontend_demandeur_projet_ajout', methods: ['GET','POST'])]
    public function ajout(Request $request): Response
    {

        $projet = new Projet();
        $form = $this->createForm(ProjetFormType::class, $projet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $projet->setReference($this->utilities->referenceProjet());
            $projet->setCreatedAt($this->utilities->fuseauGMT());
            $projet->setUser($this->getUser());
            $projet->setStatut(Utilities::PROJET_APPEL);
            $projet->setMedia(null);

            $medias = $form->get('media')->getData();
            foreach ($medias as $mediaFile){
                $projetImage = new ProjetImage();
                $media = $this->gestionMedia->upload($mediaFile, 'projet');
                $projetImage->setMedia($media);
                $projetImage->setProjet($projet);

                $this->entityManager->persist($projetImage);
            }

            $this->entityManager->persist($projet);
            $this->entityManager->flush();

            notyf()->success("Votre projet a été ajouté avec succès sous la référence '{$projet->getReference()}' !");

            return $this->redirectToRoute('app_frontend_demandeur_projet_details', [
                'demandeur' => $this->demandeur()->getCode(),
                'reference' => $projet->getReference()
            ]);
        }
        return $this->render('frontend_demandeur/projet_add.html.twig',[
            'demandeur' => $this->demandeur(),
            'projet' => $projet,
            'form' => $form
        ]);
    }

    #[Route('/{reference}', name: 'app_frontend_demandeur_projet_details', methods: ['GET'])]
    public function details($reference): Response
    {
        return $this->render('frontend_demandeur/projet_details.html.twig',[
            'demandeur' => $this->demandeur(),
            'projet' => $this->allRepositories->getProjetDetails($reference),
            'candidatures' => $this->allRepositories->findCanditatureByProjet($reference),
        ]);
    }

    #[Route('/{reference}/modification', name: 'app_frontend_demandeur_projet_modif', methods: ['GET','POST'])]
    public function modif(Request $request, $reference): Response
    {
        $projet = $this->allRepositories->getOneProjet($reference);
        $form = $this->createForm(ProjetFormType::class, $projet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->entityManager->flush();

            notyf()->success("Votre projet '{$projet->getReference()}' a été modifié avec succès!");

            return $this->redirectToRoute('app_frontend_demandeur_projet_details',[
                'demandeur' => $this->demandeur()->getCode(),
                'reference' => $projet->getReference(),
            ]);
        }

        return $this->render('frontend_demandeur/projet_edit.html.twig',[
            'demandeur' => $this->demandeur(),
            'projet' => $projet,
            'form' => $form
        ]);
    }


    #[Route('/{reference}', name: 'app_frontend_demandeur_projet_supprimer', methods: ['POST'])]
    public function supprimer(Request $request, $reference): Response
    {
        $projet = $this->allRepositories->getOneProjet($reference);
        if ($this->isCsrfTokenValid('delete'.$projet->getId(), $request->getPayload()->getString('_token'))) {
            if ($projet->getStatut() === Utilities::PROJET_DEMANDE){
                $candidature = $this->allRepositories->getDemandePrestationByProjet($projet);
                $this->entityManager->remove($candidature);
            }
            $this->entityManager->remove($projet);
            $this->entityManager->flush();

            notyf()->success(Messages::PROJET_DEMANDE_SUPPRIME, [], 'SUCCES!');
        }

        return $this->redirectToRoute('app_frontend_demandeur_projet_index',[
            'demandeur' => $this->demandeur()->getCode(),
            'projet' => $projet
        ]);
    }

    #[Route('/{reference}/cloture/projet', name: 'app_frontend_demandeur_projet_cloturer', methods: ['GET', 'POST'])]
    public function cloturer(Request $request, $reference): Response
    {
        $projet = $this->allRepositories->getOneProjet($reference);
        $postuler = $this->allRepositories->getPostulerValideByProjet($projet->getReference());

        if ($this->isCsrfTokenValid('cloturer'.$projet->getReference(), $request->getPayload()->getString('_token'))){
            $note = (int) $request->get('_cloture_rating');
            $commentaire = $request->get('_cloture_commentaire');

            $projet->setStatut(Utilities::PROJET_CLOTURE);
            $postuler->setNote($note);
            $postuler->setCommentaire($commentaire);

            $this->entityManager->flush();

            notyf()->success(Messages::PROJET_DEMANDE_CLOTURER, [], 'SUCCES');

        }

        return $this->redirectToRoute('app_frontend_demandeur_tbord');
    }

    protected function demandeur()
    {
        return $this->allRepositories->getOneDemandeur(null, $this->getUser());
    }
}

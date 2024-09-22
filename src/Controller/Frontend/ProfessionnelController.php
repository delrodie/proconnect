<?php

declare(strict_types=1);

namespace App\Controller\Frontend;

use App\Entity\Postuler;
use App\Entity\Projet;
use App\Form\EmbaucherType;
use App\Form\ReponsePrestataireType;
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

#[Route('/professionnel')]
class ProfessionnelController extends AbstractController
{
    public function __construct(
        private AllRepositories $allRepositories,
        private Utilities $utilities,
        private GestionMedia $gestionMedia,
        private EntityManagerInterface $entityManager
    )
    {
    }

    #[Route('/', name: 'app_frontend_professionnel_list')]
    public function list(): Response
    {
        return $this->render('frontend/professionnel_list.html.twig',[
            'prestataires' => $this->allRepositories->getAllPrestataire()
        ]);
    }

    #[Route('/{matricule}', name: 'app_frontend_professionnel_show', methods: ['GET'])]
    #[isGranted('ROLE_USER')]
    public function show($matricule): Response
    {

        $prestataire = $this->allRepositories->getPrestataireByMatricule($matricule);
        if (!$prestataire){
            sweetalert()->error(Messages::PRESTATAIRE_NOT_EXIST,[], 'Non trouvé!');
            return $this->redirectToRoute('app_frontend_professionnel_list');
        }

        return $this->render('frontend/professionnel_show.html.twig',[
            'prestataire' => $prestataire,
            'candidature' => $this->allRepositories->findPostulerByUser($prestataire->getUser()),
            'projet_realises' => $this->allRepositories->getProjetByUserAndCandidatureStatut($prestataire->getUser(), $this->utilities::PROJET_REALISE, $this->utilities::POSTULER_EMBAUCHE),
            'projet_encours' => $this->allRepositories->getProjetByUserAndCandidatureStatut($prestataire->getUser(), $this->utilities::PROJET_ENCOURS, $this->utilities::POSTULER_EMBAUCHE),
        ]);
    }

    #[Route('/{matricule}/embauche', name: 'app_frontend_professionnel_embauche', methods: ['GET','POST'])]
    #[isGranted('ROLE_USER')]
    public function embauche(Request $request, $matricule): Response
    {
        $prestataire = $this->allRepositories->getPrestataireByMatricule($matricule);
        if (!$prestataire){
            sweetalert()->error(Messages::PRESTATAIRE_NOT_EXIST,[], 'Non trouvé!');
            return $this->redirectToRoute('app_frontend_professionnel_list');
        }

        $demandeur = $this->allRepositories->getOneDemandeur(null, $this->getUser());
        if (!$demandeur){
            sweetalert()->error(Messages::DEMANDEUR_PROFILE_CANNOT_HIRE,[], "Vous n'êtes pas autorisés");
            return $this->redirectToRoute('app_frontend_professionnel_show',['matricule' => $matricule]);
        }

        $projet = new Projet();
        $form = $this->createForm(EmbaucherType::class, $projet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            // Gestion des medias
            $this->gestionMedia->media($form, $projet, 'projet');
            $projet->setReference($this->utilities->referenceProjet());
            $projet->setCreatedAt($this->utilities->fuseauGMT());
            $projet->setUser($this->getUser());
            $projet->setStatut(Utilities::PROJET_DEMANDE);

            $this->entityManager->persist($projet);
            $this->entityManager->flush();

            // Envoie de la demande au candidat
            $postuler = new Postuler();
            $postuler->setProjet($projet);
            $postuler->setUser($prestataire->getUser());
            $postuler->setReference($this->utilities->referencePostuler());
            $postuler->setStatut(Utilities::POSTULER_EMBAUCHE);
            $postuler->setEmbaucheAt($this->utilities->fuseauGMT());

            $this->entityManager->persist($postuler);
            $this->entityManager->flush();

            notyf()->success(Messages::PROJET_DEMANDE_EMBAUCHE, [], "SUCCES!");

            return $this->redirectToRoute("app_frontend_demandeur_projet_details",[
                'demandeur' => $demandeur->getCode(),
                'reference' => $projet->getReference()
            ]);
        }

        return $this->render('frontend_demandeur/candidat_embauche_direct.html.twig',[
            'prestataire' => $prestataire,
            'demandeur' => $demandeur,
            'form' => $form
        ]);
    }

    #[Route('/{matricule}/reponse/{reference_projet}', name: 'app_frontend_professionnel_reponse', methods: ['GET','POST'])]
    #[isGranted('ROLE_PRESTATAIRE')]
    public function reponse(Request $request, $matricule, $reference_projet): Response
    {
        // Verification du prestataire
        $prestataire = $this->allRepositories->getOnePrestataire($matricule);
        if ($prestataire->getUser() !== $this->getUser()){
            sweetalert()->error(Messages::PRESTATAIRE_NOT_IT,[],"Non attribué!");

            return $this->redirectToRoute('app_frontend_prestataire_projet_list',[
                'prestataire' => $prestataire->getMatricule()
            ]);
        }

        // Verification du projet
        $projet = $this->allRepositories->getOneProjet($reference_projet);
        if ($projet && $projet->getStatut() !== Utilities::PROJET_DEMANDE){
            sweetalert()->error(Messages::PROJET_DEMANDE_NON_DISPONIBLE,[],"DEMANDE NON DISPONIBLE");

            return $this->redirectToRoute("app_frontend_prestataire_projet_list",[
                'prestataire' => $prestataire->getMatricule()
            ]);
        }

        // Verification de la candidature
        $postuler = $this->allRepositories->findPostulerByReferenceAndUser($projet, $this->getUser());
        $form = $this->createForm(ReponsePrestataireType::class, $postuler);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $postuler->setCreatedAt($this->utilities->fuseauGMT());
            if ($postuler->getReponse() === Utilities::POSTULER_ACCEPTE) {
                $projet->setStatut(Utilities::PROJET_ENCOURS);
            }else{
                $projet->setStatut(Utilities::PROJET_APPEL);
                $postuler->setStatut(Utilities::POSTULER_DECLINE);
            }

            $this->entityManager->flush();

            sweetalert()->success(Messages::CANDIDATURE_REPONSE_DEMANDE,[],'Reponse envoyée');

            return $this->redirectToRoute('app_frontend_prestataire_projet_list',[
                'prestataire' => $prestataire->getMatricule()
            ]);
        }

        return $this->render('frontend_prestataire/demande_reponse.html.twig',[
            'prestataire' => $prestataire,
            'postuler' => $postuler,
            'form' => $form
        ]);
    }
}

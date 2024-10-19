<?php

declare(strict_types=1);

namespace App\Controller\Frontend;

use App\Entity\Prestataire;
use App\Form\PrestataireFormType;
use App\Service\AllRepositories;
use App\Service\GestionDocument;
use App\Service\GestionLicence;
use App\Service\GestionMedia;
use App\Service\Utilities;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/prestataire')]
class PrestataireController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private Utilities $utilities,
        private AllRepositories $allRepositories,
        private GestionMedia $gestionMedia,
        private GestionDocument $gestionDocument,
        private GestionLicence $gestionLicence
    )
    {
    }

    #[Route('/', name: 'app_frontend_prestataire_tbord')]
    public function index(): Response
    {
        $prestataire = $this->allRepositories->getOnePrestataire(null, $this->getUser());
        if (!$prestataire){
            notyf()->info("Veuillez renseigner votre profile");
            return $this->redirectToRoute('app_frontend_prestataire_profile');
        }

        return $this->render('frontend_prestataire/tableau_bord.html.twig',[
            'prestataire' => $prestataire,
            'soumissions' => $this->allRepositories->findPostulerByUser($this->getUser())
        ]);
    }

    #[Route('/profile', name: 'app_frontend_prestataire_profile', methods: ['GET','POST'])]
    public function profile(Request $request): Response
    {
        // Verification si le profile existe
        $verif = $this->allRepositories->getOnePrestataire(null, $this->getUser());
        if ($verif){
            return $this->redirectToRoute('app_frontend_prestataire_show',[
                'matricule' => $verif->getMatricule()
            ]);
        }

        $prestataire = new Prestataire();
        $form = $this->createForm(PrestataireFormType::class, $prestataire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            // Gestion des medias
            $this->gestionMedia->media($form, $prestataire, 'prestataire');
            $this->gestionLicence->media($form, $prestataire, 'prestataire');
            $this->gestionDocument->media($form, $prestataire, 'prestataire');

            // Matricule, user
            $prestataire->setMatricule($this->utilities->matriculePrestataire());
            $prestataire->setUser($this->getUser());
            $prestataire->setCreatedAt(new \DateTime());
            $prestataire->setSlug($this->utilities->slug($prestataire->getPrenoms().'-'.$prestataire->getNom().'-'.$prestataire->getTelephone()));

            $this->entityManager->persist($prestataire);
            $this->entityManager->flush();

            notyf()->success("Votre profile a été enregistré avec succès!");

            return $this->redirectToRoute('app_frontend_prestataire_tbord');
        }

        return  $this->render('frontend_prestataire/profile_new.html.twig',[
            'prestataire' => $prestataire,
            'form' => $form
        ]);
    }

    #[Route('/{matricule}', name: 'app_frontend_prestataire_show', methods: ['GET'])]
    public function show($matricule): Response
    {
        $prestataire = $this->allRepositories->getOnePrestataire($matricule);
        if ($prestataire->getUser() !== $this->getUser()){
            sweetalert()->error('Attention!! Vous n\'êtes pas autorisé(e) à voir ce profil ');
            return $this->redirectToRoute('app_home_index');
        }
        return $this->render('frontend_prestataire/profile_show.html.twig',[
            'prestataire' => $prestataire
        ]);
    }

    #[Route('/{matricule}/profile', name: 'app_frontend_prestataire_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, $matricule): Response
    {
        $prestataire = $this->allRepositories->getOnePrestataire($matricule);

        if ($prestataire->getUser() !== $this->getUser()){
            sweetalert()->error("Echèc! vous n'êtes pas autorisé(e) à modifier ce profile",[],"Accès non autorisé");
            return $this->redirectToRoute('app_home_index');
        }

        $form = $this->createForm(PrestataireFormType::class, $prestataire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            // Gestion des medias
            $this->gestionMedia->media($form, $prestataire, 'prestataire');
            $this->gestionLicence->media($form, $prestataire, 'prestataire');
            $this->gestionDocument->media($form, $prestataire, 'prestataire');

            $this->entityManager->flush();

            notyf()->success("Votre profile a été modifié avec succès!");

            return $this->redirectToRoute('app_frontend_prestataire_profile');
        }

        return $this->render('frontend_prestataire/profile_edit.html.twig',[
            'prestataire' => $prestataire,
            'form' => $form,
        ]);
    }
}

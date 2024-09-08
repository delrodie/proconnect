<?php

declare(strict_types=1);

namespace App\Controller\Frontend;

use App\Entity\Demandeur;
use App\Form\DemandeurFormType;
use App\Service\AllRepositories;
use App\Service\GestionMedia;
use App\Service\Utilities;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/demandeur')]
class DemandeurController extends AbstractController
{
    public function __construct(
        private Utilities $utilities,
        private EntityManagerInterface $entityManager,
        private GestionMedia $gestionMedia,
        private AllRepositories $allRepositories
    )
    {
    }

    #[Route('/', name: 'app_frontend_demandeur_tbord')]
    public function tbord(): Response
    {
        $verif = $this->allRepositories->getOneDemandeur(null, $this->getUser());
        $demandeur = $verif;
        if (!$verif){
            return $this->redirectToRoute('app_frontend_demandeur_profile');
        }


        return $this->render('frontend_demandeur/tableau_bord.html.twig',[
            'menu' => 'tbord',
            'demandeur' => $demandeur
        ]);
    }

    #[Route('/profile', name: 'app_frontend_demandeur_profile', methods: ['GET', 'POST'])]
    public function profile(Request $request): Response
    {
        // Verification si le profile existe deja
        $verif = $this->allRepositories->getOneDemandeur(null, $this->getUser());
        if ($verif){
            return $this->redirectToRoute('app_frontend_demandeur_show',[
                'code' => $verif->getCode(),
            ]);
        }

        $demandeur = new Demandeur();
        $form = $this->createForm(DemandeurFormType::class, $demandeur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            // Gestion des medias, code et slug
            $this->gestionMedia->media($form, $demandeur, 'demandeur');
            $demandeur->setCode($this->utilities->codeDemandeur());
            $demandeur->setSlug($this->utilities->slug(
                $demandeur->getNom().'-'
                .$demandeur->getPrenom().'-'
                .$this->getUser()->getUserIdentifier()
            ));
            $demandeur->setUser($this->getUser());

//            dd($this->getUser());

            $this->entityManager->persist($demandeur);
            $this->entityManager->flush();

            notyf()->success('Votre profile a été enregistré avec succès!');

            return $this->redirectToRoute('app_frontend_demandeur_show',[
                'code' => $demandeur->getCode(),
            ]);
        }
        return $this->render('frontend_demandeur/profile.html.twig', [
            'demandeur' => $demandeur,
            'form' => $form,
            'menu' => 'profile'
        ]);
    }

    #[Route('/{code}', name: 'app_frontend_demandeur_show', methods: ['GET'])]
    public function show($code)
    {
        $demandeur = $this->allRepositories->getOneDemandeur($code);
        if ($demandeur->getUser() !== $this->getUser()){
            sweetalert()->error("Echèc! vous n'êtes pas autorisé(e) à modifier ce profile",[],"Accès non autorisé");
            return $this->redirectToRoute('app_home_index');
        }

        return $this->render('frontend_demandeur/profile_show.html.twig',[
            'demandeur' => $demandeur
        ]);
    }

    #[Route('/{code}/profile', name: 'app_frontend_demandeur_edit', methods: ['GET','POST'])]
    public function edit(Request $request, $code): Response
    {
        $demandeur = $this->allRepositories->getOneDemandeur($code);
        if ($demandeur->getUser() !== $this->getUser()){
            sweetalert()->error("Echèc! vous n'êtes pas autorisé(e) à modifier ce profile",[],"Accès non autorisé");
            return $this->redirectToRoute('app_home_index');
        }

        $form = $this->createForm(DemandeurFormType::class, $demandeur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            notyf()->success("Votre profile a été modifié avec succès!");

            return $this->redirectToRoute('app_frontend_demandeur_show',[
                'code' => $code
            ]);
        }

        return $this->render('frontend_demandeur/profile_edit.html.twig',[
            'demandeur' => $demandeur,
            'form' => $form
        ]);
    }
}

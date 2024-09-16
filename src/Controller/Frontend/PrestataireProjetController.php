<?php

declare(strict_types=1);

namespace App\Controller\Frontend;

use App\Form\PostulerFormType;
use App\Service\AllRepositories;
use App\Service\Utilities;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/prestataire-{prestataire}')]
class PrestataireProjetController extends AbstractController
{
    public function __construct(
        private AllRepositories $allRepositories,
        private Utilities $utilities,
        private EntityManagerInterface $entityManager
    )
    {
    }

    #[Route('/', name: "app_frontend_prestataire_projet_list")]
    public function index(): Response
    {
        $prestataire = $this->allRepositories->getOnePrestataire(null, $this->getUser());
        return $this->render('frontend_prestataire/projet_list.html.twig',[
            'prestataire' => $prestataire,
            'soumissions' => $this->allRepositories->findPostulerByUser($this->getUser())
        ]);
    }

    #[Route('/{reference}/modification', name: 'app_frontend_prestataire_projet_modif', methods: ['GET','POST'])]
    public function modif(Request $request, $reference)
    {
        $prestataire = $this->allRepositories->getOnePrestataire(null, $this->getUser());

        $postuler = $this->allRepositories->getOnePostuler($reference);
        $form = $this->createForm(PostulerFormType::class, $postuler);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $this->entityManager->flush();

            notyf()->success($this->utilities->messageAlert(202), [], "SUCCES");

            return $this->redirectToRoute('app_frontend_prestataire_projet_list', ['prestataire' => $prestataire->getMatricule()]);
        }

        return $this->render('frontend_prestataire/projet_edit.html.twig',[
            'postuler' => $postuler,
            'prestataire' => $prestataire,
            'form' => $form
        ]);
    }

    #[Route('/mes-projets', name: 'app_frontend_prestataire_projet_valide', methods: ['GET'])]
    public function projets(): Response
    {
        $prestataire = $this->allRepositories->getOnePrestataire(null, $this->getUser());
        return $this->render('frontend_prestataire/projet_valide.html.twig',[
            'prestataire' => $prestataire,
            'valides' => $this->allRepositories->getCandidatureValideByPrestataire($this->getUser())
        ]);
    }

    #[Route('/{reference}', name: 'app_frontend_prestataire_projet_supprimer', methods: ['POST'])]
    public function supprimer(Request $request, $reference)
    {
        $prestataire = $this->allRepositories->getOnePrestataire(null, $this->getUser());

        $postuler = $this->allRepositories->getOnePostuler($reference); //dd($postuler);
        if ($this->isCsrfTokenValid('delete'.$postuler->getId(), $request->getPayload()->getString('_token'))) {
            $this->entityManager->remove($postuler);
            $this->entityManager->flush();
        }

        notyf()->success($this->utilities->messageAlert(203),[], "SUCCES");

        return $this->redirectToRoute('app_frontend_prestataire_projet_list',[
            'prestataire' => $prestataire->getMatricule(),
        ]);
    }
}

<?php

namespace App\Controller\Backend;

use App\Entity\Demandeur;
use App\Form\DemandeurType;
use App\Repository\DemandeurRepository;
use App\Service\AllRepositories;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/backend/demandeur')]
class BackendDemandeurController extends AbstractController
{

    public function __construct(
        private AllRepositories $allRepositories
    )
    {
    }

    #[Route('/', name: 'app_backend_demandeur_index', methods: ['GET'])]
    public function index(DemandeurRepository $demandeurRepository): Response
    {
        return $this->render('backend_demandeur/index.html.twig', [
            'demandeurs' => $demandeurRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_backend_demandeur_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $demandeur = new Demandeur();
        $form = $this->createForm(DemandeurType::class, $demandeur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($demandeur);
            $entityManager->flush();

            return $this->redirectToRoute('app_backend_demandeur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('backend_demandeur/new.html.twig', [
            'demandeur' => $demandeur,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_backend_demandeur_show', methods: ['GET'])]
    public function show(Demandeur $demandeur): Response
    {
        return $this->render('backend_demandeur/show.html.twig', [
            'demandeur' => $demandeur,
            'projets' => $this->allRepositories->getProjetByUser($demandeur->getUser())
        ]);
    }

    #[Route('/{id}/edit', name: 'app_backend_demandeur_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Demandeur $demandeur, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DemandeurType::class, $demandeur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_backend_demandeur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('backend_demandeur/edit.html.twig', [
            'demandeur' => $demandeur,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_backend_demandeur_delete', methods: ['POST'])]
    public function delete(Request $request, Demandeur $demandeur, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$demandeur->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($demandeur);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_backend_demandeur_index', [], Response::HTTP_SEE_OTHER);
    }
}

<?php

namespace App\Controller\Backend;

use App\Entity\Prestataire;
use App\Form\PrestataireType;
use App\Repository\PrestataireRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/backend/prestataire')]
class BackendPrestataireController extends AbstractController
{
    #[Route('/', name: 'app_backend_prestataire_index', methods: ['GET'])]
    public function index(PrestataireRepository $prestataireRepository): Response
    {
        return $this->render('backend_prestataire/index.html.twig', [
            'prestataires' => $prestataireRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_backend_prestataire_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $prestataire = new Prestataire();
        $form = $this->createForm(PrestataireType::class, $prestataire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($prestataire);
            $entityManager->flush();

            return $this->redirectToRoute('app_backend_prestataire_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('backend_prestataire/new.html.twig', [
            'prestataire' => $prestataire,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_backend_prestataire_show', methods: ['GET'])]
    public function show(Prestataire $prestataire): Response
    {
        return $this->render('backend_prestataire/show.html.twig', [
            'prestataire' => $prestataire,
            'projets' => []
        ]);
    }

    #[Route('/{id}/edit', name: 'app_backend_prestataire_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Prestataire $prestataire, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PrestataireType::class, $prestataire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_backend_prestataire_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('backend_prestataire/edit.html.twig', [
            'prestataire' => $prestataire,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_backend_prestataire_delete', methods: ['POST'])]
    public function delete(Request $request, Prestataire $prestataire, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$prestataire->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($prestataire);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_backend_prestataire_index', [], Response::HTTP_SEE_OTHER);
    }
}

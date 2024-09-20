<?php

namespace App\Controller\Backend;

use App\Entity\Parallax;
use App\Form\ParallaxType;
use App\Repository\ParallaxRepository;
use App\Service\GestionMedia;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/backend/parallax')]
class BackendParallaxController extends AbstractController
{
    public function __construct(
        private GestionMedia $gestionMedia
    )
    {
    }

    #[Route('/', name: 'app_backend_parallax_index', methods: ['GET'])]
    public function index(ParallaxRepository $parallaxRepository): Response
    {
        return $this->render('backend_parallax/index.html.twig', [
            'parallaxes' => $parallaxRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_backend_parallax_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $parallax = new Parallax();
        $form = $this->createForm(ParallaxType::class, $parallax);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Gestion des medias
            $this->gestionMedia->media($form, $parallax, 'parallax');

            $entityManager->persist($parallax);
            $entityManager->flush();

            flash()->success("Le prallax '{$parallax->getTitle()}' a été ajouté avec succès");

            return $this->redirectToRoute('app_backend_parallax_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('backend_parallax/new.html.twig', [
            'parallax' => $parallax,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_backend_parallax_show', methods: ['GET'])]
    public function show(Parallax $parallax): Response
    {
        return $this->render('backend_parallax/show.html.twig', [
            'parallax' => $parallax,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_backend_parallax_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Parallax $parallax, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ParallaxType::class, $parallax);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Gestion des medias
            $this->gestionMedia->media($form, $parallax, 'parallax');

            $entityManager->flush();

            flash()->success("Le prallax '{$parallax->getTitle()}' a été modifié avec succès");

            return $this->redirectToRoute('app_backend_parallax_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('backend_parallax/edit.html.twig', [
            'parallax' => $parallax,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_backend_parallax_delete', methods: ['POST'])]
    public function delete(Request $request, Parallax $parallax, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$parallax->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($parallax);
            $entityManager->flush();

            flash()->success("Le prallax '{$parallax->getTitle()}' a été supprimé avec succès");
        }

        return $this->redirectToRoute('app_backend_parallax_index', [], Response::HTTP_SEE_OTHER);
    }
}

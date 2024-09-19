<?php

namespace App\Controller\Backend;

use App\Entity\Slide;
use App\Form\SlideType;
use App\Repository\SlideRepository;
use App\Service\GestionMedia;
use App\Service\Utilities;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/backend/slide')]
class BackendSlideController extends AbstractController
{
    public function __construct(
        private GestionMedia $gestionMedia,
        private Utilities $utilities
    )
    {
    }

    #[Route('/', name: 'app_backend_slide_index', methods: ['GET'])]
    public function index(SlideRepository $slideRepository): Response
    {
        return $this->render('backend_slide/index.html.twig', [
            'slides' => $slideRepository->findBy([],['id'=>"DESC"]),
        ]);
    }

    #[Route('/new', name: 'app_backend_slide_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $slide = new Slide();
        $form = $this->createForm(SlideType::class, $slide);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Gestion des medias
            $this->gestionMedia->media($form, $slide, 'slide');

            $entityManager->persist($slide);
            $entityManager->flush();

            return $this->redirectToRoute('app_backend_slide_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('backend_slide/new.html.twig', [
            'slide' => $slide,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_backend_slide_show', methods: ['GET'])]
    public function show(Slide $slide): Response
    {
        return $this->render('backend_slide/show.html.twig', [
            'slide' => $slide,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_backend_slide_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Slide $slide, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SlideType::class, $slide);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_backend_slide_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('backend_slide/edit.html.twig', [
            'slide' => $slide,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_backend_slide_delete', methods: ['POST'])]
    public function delete(Request $request, Slide $slide, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$slide->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($slide);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_backend_slide_index', [], Response::HTTP_SEE_OTHER);
    }
}

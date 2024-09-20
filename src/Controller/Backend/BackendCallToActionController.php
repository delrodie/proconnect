<?php

namespace App\Controller\Backend;

use App\Entity\CallToAction;
use App\Form\CallToActionType;
use App\Repository\CallToActionRepository;
use App\Service\GestionMedia;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/backend/call-to-action')]
class BackendCallToActionController extends AbstractController
{
    public function __construct(
        private GestionMedia $gestionMedia
    )
    {
    }

    #[Route('/', name: 'app_backend_call_to_action_index', methods: ['GET'])]
    public function index(CallToActionRepository $callToActionRepository): Response
    {
        return $this->render('backend_call_to_action/index.html.twig', [
            'call_to_actions' => $callToActionRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_backend_call_to_action_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $callToAction = new CallToAction();
        $form = $this->createForm(CallToActionType::class, $callToAction);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Gestion des medias
            $this->gestionMedia->media($form, $callToAction, 'action');

            $entityManager->persist($callToAction);
            $entityManager->flush();

            flash()->success("L'action pour '{$callToAction->getType()}' a été ajouté avec succès!");

            return $this->redirectToRoute('app_backend_call_to_action_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('backend_call_to_action/new.html.twig', [
            'call_to_action' => $callToAction,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_backend_call_to_action_show', methods: ['GET'])]
    public function show(CallToAction $callToAction): Response
    {
        return $this->render('backend_call_to_action/show.html.twig', [
            'call_to_action' => $callToAction,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_backend_call_to_action_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, CallToAction $callToAction, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CallToActionType::class, $callToAction);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Gestion des medias
            $this->gestionMedia->media($form, $callToAction, 'action');

            $entityManager->flush();

            flash()->success("L'action pour '{$callToAction->getType()}' a été modifiée avec succès!");

            return $this->redirectToRoute('app_backend_call_to_action_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('backend_call_to_action/edit.html.twig', [
            'call_to_action' => $callToAction,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_backend_call_to_action_delete', methods: ['POST'])]
    public function delete(Request $request, CallToAction $callToAction, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$callToAction->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($callToAction);
            $entityManager->flush();

            flash()->success("L'action pour '{$callToAction->getType()}' a été supprimée avec succès!");
        }

        return $this->redirectToRoute('app_backend_call_to_action_index', [], Response::HTTP_SEE_OTHER);
    }
}

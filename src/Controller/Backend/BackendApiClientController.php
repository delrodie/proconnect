<?php

namespace App\Controller\Backend;

use App\Entity\ApiClient;
use App\Form\ApiClientType;
use App\Repository\ApiClientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/backend/api/client')]
class BackendApiClientController extends AbstractController
{
    #[Route('/', name: 'app_backend_api_client_index', methods: ['GET', 'POST'])]
    public function index(ApiClientRepository $apiClientRepository, Request $request, EntityManagerInterface $entityManager): Response
    {
        $apiClient = new ApiClient();
        $form = $this->createForm(ApiClientType::class, $apiClient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $apiClient->setActive(true);
            $entityManager->persist($apiClient);
            $entityManager->flush();

            return $this->redirectToRoute('app_backend_api_client_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('backend_api_client/index.html.twig', [
            'api_clients' => $apiClientRepository->findAll(),
            'api_client' => $apiClient,
            'form' => $form,
            'suppression' => false
        ]);
    }

    #[Route('/new', name: 'app_backend_api_client_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $apiClient = new ApiClient();
        $form = $this->createForm(ApiClientType::class, $apiClient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($apiClient);
            $entityManager->flush();

            return $this->redirectToRoute('app_backend_api_client_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('backend_api_client/new.html.twig', [
            'api_client' => $apiClient,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_backend_api_client_show', methods: ['GET'])]
    public function show(ApiClient $apiClient): Response
    {
        return $this->render('backend_api_client/show.html.twig', [
            'api_client' => $apiClient,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_backend_api_client_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ApiClient $apiClient, EntityManagerInterface $entityManager, ApiClientRepository $apiClientRepository): Response
    {
        $form = $this->createForm(ApiClientType::class, $apiClient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_backend_api_client_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('backend_api_client/edit.html.twig', [
            'api_client' => $apiClient,
            'form' => $form,
            'api_clients' => $apiClientRepository->findAll(),
            'suppression' => true
        ]);
    }

    #[Route('/{id}', name: 'app_backend_api_client_delete', methods: ['POST'])]
    public function delete(Request $request, ApiClient $apiClient, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$apiClient->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($apiClient);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_backend_api_client_index', [], Response::HTTP_SEE_OTHER);
    }
}

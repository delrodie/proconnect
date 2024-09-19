<?php

namespace App\Controller\Backend;

use App\Entity\Localite;
use App\Form\LocaliteType;
use App\Repository\LocaliteRepository;
use App\Service\Utilities;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/backend/localite')]
class BackendLocaliteController extends AbstractController
{
    public function __construct(
        private Utilities $utilities
    )
    {
    }

    #[Route('/', name: 'app_backend_localite_index', methods: ['GET', 'POST'])]
    public function index(Request $request, EntityManagerInterface $entityManager, LocaliteRepository $localiteRepository): Response
    {
        $localite = new Localite();
        $form = $this->createForm(LocaliteType::class, $localite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Generation du slug et verification de son existence dans la table domaine
            $slug = $this->utilities->entityExiste($localite->getTitle(), 'localite');
            if (!$slug){
                sweetalert()->error("La localité '{$localite->getTitle()}' existe déjà");
                return $this->redirectToRoute('app_backend_localite_index', [], Response::HTTP_SEE_OTHER);
            }

            $localite->setSlug($slug);

            $entityManager->persist($localite);
            $entityManager->flush();

            sweetalert()->success("La localité '{$localite->getTitle()}' a été ajoutée avec succès!");

            return $this->redirectToRoute('app_backend_localite_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('backend_localite/index.html.twig', [
            'localites' => $localiteRepository->findAll(),
            'localite' => $localite,
            'form' => $form,
            'suppression' => false
        ]);
    }

    #[Route('/new', name: 'app_backend_localite_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $localite = new Localite();
        $form = $this->createForm(LocaliteType::class, $localite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($localite);
            $entityManager->flush();

            return $this->redirectToRoute('app_backend_localite_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('backend_localite/new.html.twig', [
            'localite' => $localite,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_backend_localite_show', methods: ['GET'])]
    public function show(Localite $localite): Response
    {
        return $this->render('backend_localite/show.html.twig', [
            'localite' => $localite,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_backend_localite_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Localite $localite, EntityManagerInterface $entityManager, LocaliteRepository $localiteRepository): Response
    {
        $form = $this->createForm(LocaliteType::class, $localite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $localite->setSlug($this->utilities->slug($localite->getTitle()));
            $entityManager->flush();

            sweetalert()->success("La localité '{$localite->getTitle()}' a été modifiée avec succès!");

            return $this->redirectToRoute('app_backend_localite_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('backend_localite/edit.html.twig', [
            'localites' => $localiteRepository->findAll(),
            'localite' => $localite,
            'form' => $form,
            'suppression' => true
        ]);
    }

    #[Route('/{id}', name: 'app_backend_localite_delete', methods: ['POST'])]
    public function delete(Request $request, Localite $localite, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$localite->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($localite);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_backend_localite_index', [], Response::HTTP_SEE_OTHER);
    }
}

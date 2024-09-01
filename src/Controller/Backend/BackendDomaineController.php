<?php

namespace App\Controller\Backend;

use App\Entity\Domaine;
use App\Form\DomaineType;
use App\Repository\DomaineRepository;
use App\Service\AllRepositories;
use App\Service\Utilities;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/backend/domaine')]
class BackendDomaineController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private AllRepositories $allRepositories,
        private Utilities $utilities
    )
    {
    }

    #[Route('/', name: 'app_backend_domaine_index', methods:  ['GET', 'POST'])]
    public function index(Request $request, DomaineRepository $domaineRepository): Response
    {
        $domaine = new Domaine();
        $form = $this->createForm(DomaineType::class, $domaine);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Generation du slug et verification de son existence dans la table domaine
            $slug = $this->utilities->entityExiste($domaine->getTitle(), 'domaine');
            if (!$slug){
                sweetalert()->error("Le domaine '{$domaine->getTitle()}' existe déjà");
                return $this->redirectToRoute('app_backend_domaine_index', [], Response::HTTP_SEE_OTHER);
            }

            $domaine->setSlug($slug);

            $this->entityManager->persist($domaine);
            $this->entityManager->flush();

            sweetalert()->success("Le domaine {$domaine->getTitle()} a été ajouté avec succèc!");

            return $this->redirectToRoute('app_backend_domaine_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('backend_domaine/index.html.twig', [
            'domaines' => $domaineRepository->findAll(),
            'domaine' => $domaine,
            'form' => $form,
            'suppression' => false
        ]);
    }

    #[Route('/new', name: 'app_backend_domaine_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $domaine = new Domaine();
        $form = $this->createForm(DomaineType::class, $domaine);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($domaine);
            $entityManager->flush();

            return $this->redirectToRoute('app_backend_domaine_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('backend_domaine/new.html.twig', [
            'domaine' => $domaine,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_backend_domaine_show', methods: ['GET'])]
    public function show(Domaine $domaine): Response
    {
        return $this->render('backend_domaine/show.html.twig', [
            'domaine' => $domaine,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_backend_domaine_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Domaine $domaine, EntityManagerInterface $entityManager, DomaineRepository $domaineRepository): Response
    {
        $form = $this->createForm(DomaineType::class, $domaine);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $domaine->setSlug($this->utilities->slug($domaine->getTitle()));
            $entityManager->flush();

            sweetalert()->success("Le domaine '{$domaine->getTitle()}' a été modifié avec succès!");

            return $this->redirectToRoute('app_backend_domaine_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('backend_domaine/edit.html.twig', [
            'domaines' => $domaineRepository->findAll(),
            'domaine' => $domaine,
            'form' => $form,
            'suppression' => true
        ]);
    }

    #[Route('/{id}', name: 'app_backend_domaine_delete', methods: ['POST'])]
    public function delete(Request $request, Domaine $domaine, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$domaine->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($domaine);
            $entityManager->flush();

            sweetalert()->success("Le domaine '{$domaine->getTitle()}' a été supprimé avec succès!");
        }

        return $this->redirectToRoute('app_backend_domaine_index', [], Response::HTTP_SEE_OTHER);
    }
}

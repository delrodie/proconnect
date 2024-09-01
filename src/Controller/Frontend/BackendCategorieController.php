<?php

namespace App\Controller\Frontend;

use App\Entity\Categorie;
use App\Form\CategorieType;
use App\Repository\CategorieRepository;
use App\Service\AllRepositories;
use App\Service\Utilities;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/backend/categorie')]
class BackendCategorieController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private AllRepositories $allRepositories,
        private Utilities $utilities
    )
    {
    }

    #[Route('/', name: 'app_backend_categorie_index', methods: ['GET', 'POST'])]
    public function index(Request $request, CategorieRepository $categorieRepository): Response
    {
        $categorie = new Categorie();
        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Slug et verification d'existence
            $slug = $this->utilities->entityExiste($categorie->getTitle(), 'categorie');
            if (!$slug){
                sweetalert()->error("La catégorie '{$categorie->getTitle()}' existe déjà!");
                return $this->redirectToRoute('app_backend_categorie_index',[], Response::HTTP_SEE_OTHER);
            }
            $categorie->setSlug($slug);

            $this->entityManager->persist($categorie);
            $this->entityManager->flush();

            sweetalert()->success("La catégorie '{$categorie->getTitle()}' a été ajoutée avec succès!");

            return $this->redirectToRoute('app_backend_categorie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('backend_categorie/index.html.twig', [
            'categories' => $categorieRepository->findAll(),
            'categorie' => $categorie,
            'form' => $form,
            'suppression' => false
        ]);
    }

    #[Route('/new', name: 'app_backend_categorie_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $categorie = new Categorie();
        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($categorie);
            $entityManager->flush();

            return $this->redirectToRoute('app_backend_categorie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('backend_categorie/new.html.twig', [
            'categorie' => $categorie,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_backend_categorie_show', methods: ['GET'])]
    public function show(Categorie $categorie): Response
    {
        return $this->render('backend_categorie/show.html.twig', [
            'categorie' => $categorie,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_backend_categorie_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Categorie $categorie, EntityManagerInterface $entityManager, CategorieRepository $categorieRepository): Response
    {
        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $categorie->setSlug($this->utilities->slug($categorie->getTitle()));
            $entityManager->flush();

            sweetalert()->success("La catégorie '{$categorie->getTitle()}' a été modifiée avec succès!");

            return $this->redirectToRoute('app_backend_categorie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('backend_categorie/edit.html.twig', [
            'categories' => $categorieRepository->findAll(),
            'categorie' => $categorie,
            'form' => $form,
            'suppression' => true
        ]);
    }

    #[Route('/{id}', name: 'app_backend_categorie_delete', methods: ['POST'])]
    public function delete(Request $request, Categorie $categorie, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$categorie->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($categorie);
            $entityManager->flush();

            sweetalert()->success("La catégorie '{$categorie->getTitle()}' a été supprimée avec succès!");
        }

        return $this->redirectToRoute('app_backend_categorie_index', [], Response::HTTP_SEE_OTHER);
    }
}

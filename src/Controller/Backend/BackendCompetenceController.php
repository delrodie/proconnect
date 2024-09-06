<?php

namespace App\Controller\Backend;

use App\Entity\Competence;
use App\Form\CompetenceType;
use App\Repository\CompetenceRepository;
use App\Service\Utilities;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/backend/competence')]
class BackendCompetenceController extends AbstractController
{
    public function __construct(
        private Utilities $utilities
    )
    {
    }

    #[Route('/', name: 'app_backend_competence_index', methods: ['GET', 'POST'])]
    public function index(Request $request, EntityManagerInterface $entityManager, CompetenceRepository $competenceRepository): Response
    {
        $competence = new Competence();
        $form = $this->createForm(CompetenceType::class, $competence);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Generation du slug et verification de son existence dans la table domaine
            $slug = $this->utilities->entityExiste($competence->getTitle(), 'competence');
            if (!$slug){
                sweetalert()->error("La compétence '{$competence->getTitle()}' existe déjà");
                return $this->redirectToRoute('app_backend_domaine_index', [], Response::HTTP_SEE_OTHER);
            }

            $competence->setSlug($slug);

            $entityManager->persist($competence);
            $entityManager->flush();

            sweetalert()->success("La compétence {$competence->getTitle()} a été ajoutée avec succès!");

            return $this->redirectToRoute('app_backend_competence_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('backend_competence/index.html.twig', [
            'competences' => $competenceRepository->findAll(),
            'competence' => $competence,
            'form' => $form,
            'suppression' => false
        ]);
    }

    #[Route('/new', name: 'app_backend_competence_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $competence = new Competence();
        $form = $this->createForm(CompetenceType::class, $competence);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($competence);
            $entityManager->flush();

            return $this->redirectToRoute('app_backend_competence_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('backend_competence/new.html.twig', [
            'competence' => $competence,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_backend_competence_show', methods: ['GET'])]
    public function show(Competence $competence): Response
    {
        return $this->render('backend_competence/show.html.twig', [
            'competence' => $competence,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_backend_competence_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Competence $competence, EntityManagerInterface $entityManager, CompetenceRepository $competenceRepository): Response
    {
        $form = $this->createForm(CompetenceType::class, $competence);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $competence->setSlug($this->utilities->slug($competence->getTitle()));
            $entityManager->flush();

            sweetalert()->success("La compétence {$competence->getTitle()} a été modifiée avec succès!");

            return $this->redirectToRoute('app_backend_competence_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('backend_competence/edit.html.twig', [
            'competences' => $competenceRepository->findAll(),
            'competence' => $competence,
            'form' => $form,
            'suppression' => true
        ]);
    }

    #[Route('/{id}', name: 'app_backend_competence_delete', methods: ['POST'])]
    public function delete(Request $request, Competence $competence, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$competence->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($competence);
            $entityManager->flush();

            sweetalert()->success("La compétence '{$competence->getTitle()}' a été supprimée avec succès!");
        }

        return $this->redirectToRoute('app_backend_competence_index', [], Response::HTTP_SEE_OTHER);
    }
}

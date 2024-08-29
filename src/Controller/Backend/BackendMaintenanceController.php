<?php

declare(strict_types=1);

namespace App\Controller\Backend;

use App\Entity\Maintenance;
use App\Form\MaintenanceType;
use App\Service\AllRepositories;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/backend/maintenance')]
class BackendMaintenanceController extends AbstractController
{
    public function __construct(
        private AllRepositories $allRepositories,
        private EntityManagerInterface $entityManager
    )
    {
    }

    #[Route('/', name: 'app_backend_maintenance_toggle', methods: ['GET', 'POST'])]
    public function toggle(Request $request): Response
    {
        // Verification du statut de la maintenance
        $maintenance = $this->allRepositories->isMaintenance();
        if ($maintenance){
            return $this->redirectToRoute('app_backend_maintenance_edit',[
                'id' => $maintenance->getId(),
            ], Response::HTTP_SEE_OTHER);
        }

        // Activation de la maintenance
        $maintenance = new Maintenance();
        $form = $this->createForm(MaintenanceType::class, $maintenance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $this->entityManager->persist($maintenance);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_backend_maintenance_edit',[
                'id' => $maintenance->getId()
            ], Response::HTTP_SEE_OTHER);
        }

        return $this->render('backend/maintenance.html.twig',[
            'maintenance' => $maintenance,
            'form' => $form
        ]);
    }

    #[Route('/{id}', name: 'app_backend_maintenance_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Maintenance $maintenance): Response
    {
        $form = $this->createForm(MaintenanceType::class, $maintenance);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $this->entityManager->flush();

            return $this->redirectToRoute('app_backend_maintenance_edit',[
                'id' => $maintenance->getId()
            ], Response::HTTP_SEE_OTHER);
        }

        return $this->render('backend/maintenance.html.twig',[
            'maintenance' => $maintenance,
            'form' => $form
        ]);
    }
}

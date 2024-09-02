<?php

namespace App\Service;

use App\Repository\CategorieRepository;
use App\Repository\DemandeurRepository;
use App\Repository\DomaineRepository;
use App\Repository\MaintenanceRepository;
use App\Repository\ProjetRepository;

class AllRepositories
{
    public function __construct(
        private MaintenanceRepository $maintenanceRepository,
        private DemandeurRepository $demandeurRepository,
        private DomaineRepository $domaineRepository,
        private CategorieRepository $categorieRepository,
        private ProjetRepository $projetRepository
    )
    {
    }

    public function isMaintenance()
    {
        $maintenance = $this->maintenanceRepository->findOneBy(['active' => true]);
        if ($maintenance){
            return $maintenance;
        }

        return false;
    }

    public function getOneDemandeur(string $code = null, object $user = null)
    {
        if ($code){
            return $this->demandeurRepository->findOneBy(['code' => $code]);
        }

        if ($user){
            return $this->demandeurRepository->findOneBy(['user' => $user]);
        }

        return $this->demandeurRepository->findOneBy([], ['id' => 'DESC']);
    }

    public function getOneDomaine(string $slug = null)
    {
        if ($slug){
            return $this->domaineRepository->findOneBy(['slug' => $slug]);
        }

        return $this->domaineRepository->findOneBy([], ['id' => "DESC"]);
    }

    public function getOneCategorie(string $slug = null)
    {
        if ($slug){
            return $this->categorieRepository->findOneBy(['slug' => $slug]);
        }

        return $this->categorieRepository->findOneBy([], ['id' => "DESC"]);
    }

    public function getOneProjet(string $reference = null)
    {
        if ($reference){
            return $this->projetRepository->findOneBy(['reference' => $reference]);
        }

        return $this->projetRepository->findOneBy([],['id' => "DESC"]);
    }

    public function findProjetsByUser($user)
    {
        return $this->projetRepository->findBy(['user' => $user], ['createdAt' =>'DESC']);
    }
}
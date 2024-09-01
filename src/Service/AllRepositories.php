<?php

namespace App\Service;

use App\Repository\DemandeurRepository;
use App\Repository\DomaineRepository;
use App\Repository\MaintenanceRepository;

class AllRepositories
{
    public function __construct(
        private MaintenanceRepository $maintenanceRepository,
        private DemandeurRepository $demandeurRepository,
        private DomaineRepository $domaineRepository
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
}
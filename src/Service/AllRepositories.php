<?php

namespace App\Service;

use App\Repository\MaintenanceRepository;

class AllRepositories
{
    public function __construct(
        private MaintenanceRepository $maintenanceRepository
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
}
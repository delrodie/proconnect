<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Entity\Demandeur;
use App\Service\ApiRepositories;

class DemandeurStateProvider implements ProviderInterface
{
    public function __construct(private readonly ApiRepositories $apiRepositories)
    {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        if ($uriVariables){
            return $this->apiRepositories->getShowDemandeur($uriVariables['id']);
        }

        return $this->apiRepositories->getListDemandeur();

    }
}

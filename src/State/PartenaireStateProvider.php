<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\DTO\PartenaireOutput;
use App\Service\ApiRepositories;

class PartenaireStateProvider implements ProviderInterface
{
    public function __construct(private readonly ApiRepositories $apiRepositories)
    {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        if (isset($uriVariables['id'])){
            $partenaire =  $this->apiRepositories->getShowPartenaire($uriVariables['id']);
            return new PartenaireOutput(
                $partenaire->getId(),
                $partenaire->getNom(),
                $this->apiRepositories->generateMediaUrl($partenaire->getMedia(), 'partenaire'),
            );
        }

        return $this->apiRepositories->getListPartenaires();
    }
}

<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Service\ApiRepositories;

class DomaineStateProvider implements ProviderInterface
{
    public function __construct(private readonly ApiRepositories $apiRepositories)
    {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
//        dd($uriVariables['id']);
        return $this->apiRepositories->showDomaine($uriVariables['id']);
    }
}

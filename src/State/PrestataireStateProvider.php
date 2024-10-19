<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Service\ApiRepositories;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PrestataireStateProvider implements ProviderInterface
{
    public function __construct(private readonly ApiRepositories $apiRepositories)
    {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        if ($uriVariables){
            $prestataire =  $this->apiRepositories->getShowPrestataire($uriVariables['id']);

        }
        return $this->apiRepositories->getListPrestataire();
    }
}

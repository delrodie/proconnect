<?php

namespace App\Twig\Runtime;

use App\Service\AllRepositories;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Extension\RuntimeExtensionInterface;

class ProjetDemandeurRuntime implements RuntimeExtensionInterface
{
    public function __construct(
        private AllRepositories $allRepositories,
        private RequestStack $requestStack
    )
    {
        // Inject dependencies if needed
    }

    public function projetDemandeur($value): string
    {
        $projet = $this->allRepositories->getOneProjet($value);

        $demandeur = $this->allRepositories->getDemandeurByUser($projet->getUser());

        return $demandeur->getPrenom().' '.$demandeur->getNom();
    }

    public function projetDemandeurCode($value): ?string
    {
        $projet = $this->allRepositories->getOneProjet($value);
        return $this->allRepositories->getDemandeurByUser($projet->getUser())->getCode();
    }


}

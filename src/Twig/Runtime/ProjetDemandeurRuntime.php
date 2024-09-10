<?php

namespace App\Twig\Runtime;

use App\Service\AllRepositories;
use Twig\Extension\RuntimeExtensionInterface;

class ProjetDemandeurRuntime implements RuntimeExtensionInterface
{
    public function __construct(
        private AllRepositories $allRepositories
    )
    {
        // Inject dependencies if needed
    }

    public function projetDemandeur($value)
    {
        $projet = $this->allRepositories->getOneProjet($value);

        $demandeur = $this->allRepositories->getDemandeurByUser($projet->getUser());

        return $demandeur->getPrenom().' '.$demandeur->getNom();
    }
}

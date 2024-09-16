<?php

namespace App\Twig\Runtime;

use App\Service\AllRepositories;
use Twig\Extension\RuntimeExtensionInterface;

class ProjetRuntime implements RuntimeExtensionInterface
{
    public function __construct(
        private AllRepositories $allRepositories
    )
    {
        // Inject dependencies if needed
    }

    public function getPrestataire($value): string
    {
        $prestataire = $this->allRepositories->getPrestataireByOffre($value);

        return $prestataire->getPrenoms().' '.$prestataire->getNom();
    }

    public function getCandidature($referenceProjet)
    {
        $candidature = $this->allRepositories->getCandidatureValideByProjet($referenceProjet);

        return $candidature->getReference();
    }
}

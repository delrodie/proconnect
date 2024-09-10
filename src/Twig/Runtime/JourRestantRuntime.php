<?php

namespace App\Twig\Runtime;

use App\Service\AllRepositories;
use App\Service\Utilities;
use Twig\Extension\RuntimeExtensionInterface;

class JourRestantRuntime implements RuntimeExtensionInterface
{
    public function __construct(
        private AllRepositories $allRepositories,
        private Utilities $utilities
    )
    {
        // Inject dependencies if needed
    }

    public function jourRestant($value): string
    {
        $aujourdhui = $this->utilities->fuseauGMT();
        $projet = $this->allRepositories->getOneProjet($value);
        $difference = $projet->getDateLimite()->diff($aujourdhui);

        $jourRestants = $difference->days; //dd($jourRestants);


        return $jourRestants;
    }
}

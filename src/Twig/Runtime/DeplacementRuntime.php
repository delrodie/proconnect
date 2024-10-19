<?php

namespace App\Twig\Runtime;

use Twig\Extension\RuntimeExtensionInterface;

class DeplacementRuntime implements RuntimeExtensionInterface
{
    public function __construct()
    {
        // Inject dependencies if needed
    }

    public function modeDeplacement($value)
    {
        return match ($value){
            'PERSONNEL', 'personnel', 'Personnel' => 'Véhicule personnel',
            'COMMUN', 'commun', 'Commun' => 'Transport en commun',
            'DEUX' => 'Véhicule personnel & transport en commun',
            default => 'Non spécifié',
        };
    }
}

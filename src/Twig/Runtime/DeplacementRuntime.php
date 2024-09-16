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
            'PERSONNEL' => 'Véhicule personnel',
            'COMMUN' => 'Transport en commun',
            'DEUX' => 'Véhicule personnel & transport en commun'
        };
    }
}

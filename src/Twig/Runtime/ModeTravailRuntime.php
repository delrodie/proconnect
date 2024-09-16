<?php

namespace App\Twig\Runtime;

use Twig\Extension\RuntimeExtensionInterface;

class ModeTravailRuntime implements RuntimeExtensionInterface
{
    public function __construct()
    {
        // Inject dependencies if needed
    }

    public function modeTravail($value)
    {
        return match ($value){
            'SEUL' => 'Travail seul',
            'APPRENTIS' => 'Travail avec apprentis'
        };
    }
}

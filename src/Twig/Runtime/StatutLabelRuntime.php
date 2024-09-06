<?php

namespace App\Twig\Runtime;

use Twig\Extension\RuntimeExtensionInterface;

class StatutLabelRuntime implements RuntimeExtensionInterface
{
    public function __construct()
    {
        // Inject dependencies if needed
    }

    public function statutLabel($value): string
    {
        return match($value){
            'TERMINE' =>  'Projet réalisé',
            'ENCOURS' => 'En réalisation',
            default => "En appel"
        };
    }
}

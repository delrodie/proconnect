<?php

namespace App\Twig\Runtime;

use Twig\Extension\RuntimeExtensionInterface;

class StatutStickRuntime implements RuntimeExtensionInterface
{
    public function __construct()
    {
        // Inject dependencies if needed
    }

    public function stickCssClass($value): string
    {
        return match ($value){
            'CLOTURE' => 'text-bg-success',
            'TERMINE' => 'text-bg-success',
            'ENCOURS' => 'text-bg-warning',
            'DEMANDE' => 'text-bg-danger',
            default => 'text-bg-info'
        };
    }

    public function postulerCssClass($value)
    {
        return match ($value){
            'EMBAUCHE' => 'text-bg-success',
            'SOUMIS' => 'text-bg-warning',
            'DECLINE' => 'text-bg-secondary',
            default => 'text-bg-outline-info'
        };
    }
}

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
            'TERMINE' => 'text-bg-success',
            'ENCOURS' => 'text-bg-warning',
            default => 'text-bg-info'
        };
    }
}

<?php

namespace App\Twig\Runtime;

use Twig\Extension\RuntimeExtensionInterface;

class ExperienceRuntime implements RuntimeExtensionInterface
{
    public function __construct()
    {
        // Inject dependencies if needed
    }

    public function getExperience($value): string
    {
        return match ($value){
            '1' => '1 - 3 ans',
            '2' => '4 - 10 ans',
            '3' => '10 ans +',
            default => 'Moins d\'1 an'
        };
    }
}

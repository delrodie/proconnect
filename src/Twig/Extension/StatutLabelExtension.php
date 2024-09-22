<?php

namespace App\Twig\Extension;

use App\Twig\Runtime\StatutLabelRuntime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class StatutLabelExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/3.x/advanced.html#automatic-escaping
            new TwigFilter('statut_label', [StatutLabelRuntime::class, 'statutLabel']),
            new TwigFilter('postuler_statut_label', [StatutLabelRuntime::class, 'postulerStatutLabel']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('statut_label', [StatutLabelRuntime::class, 'statutLabel']),
            new TwigFunction('postuler_statut_label', [StatutLabelRuntime::class, 'postulerStatutLabel']),
        ];
    }
}

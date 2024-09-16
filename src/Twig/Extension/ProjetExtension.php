<?php

namespace App\Twig\Extension;

use App\Twig\Runtime\ProjetRuntime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class ProjetExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/3.x/advanced.html#automatic-escaping
            new TwigFilter('prestataire_selectionne', [ProjetRuntime::class, 'getPrestataire']),
            new TwigFilter('candidature_selectionne', [ProjetRuntime::class, 'getCandidature'])
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('prestataire_selectionne', [ProjetRuntime::class, 'getPrestataire']),
        ];
    }
}

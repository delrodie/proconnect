<?php

namespace App\Twig\Extension;

use App\Twig\Runtime\PrestataireRuntime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class PrestataireExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/3.x/advanced.html#automatic-escaping
            new TwigFilter('prestataire_rating', [PrestataireRuntime::class, 'prestataireRating']),
            new TwigFilter('prestataire_projet_realise', [PrestataireRuntime::class, 'projetRealise']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('prestataire_rating', [PrestataireRuntime::class, 'prestataireRating']),
            new TwigFunction('prestataire_projet_realise', [PrestataireRuntime::class, 'projetRealise']),
        ];
    }
}

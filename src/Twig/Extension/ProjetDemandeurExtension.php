<?php

namespace App\Twig\Extension;

use App\Twig\Runtime\ProjetDemandeurRuntime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class ProjetDemandeurExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/3.x/advanced.html#automatic-escaping
            new TwigFilter('projet_demandeur', [ProjetDemandeurRuntime::class, 'projetDemandeur']),
            new TwigFilter('projet_demandeur_code', [ProjetDemandeurRuntime::class, 'projetDemandeurCode']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('projet_demandeur', [ProjetDemandeurRuntime::class, 'projetDemandeur']),
            new TwigFunction('projet_demandeur_code', [ProjetDemandeurRuntime::class, 'projetDemandeurCode']),
        ];
    }
}

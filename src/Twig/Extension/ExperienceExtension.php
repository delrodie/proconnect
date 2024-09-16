<?php

namespace App\Twig\Extension;

use App\Twig\Runtime\ExperienceRuntime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class ExperienceExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/3.x/advanced.html#automatic-escaping
            new TwigFilter('filtre_experience', [ExperienceRuntime::class, 'getExperience']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('filtre_experience', [ExperienceRuntime::class, 'getExperience']),
        ];
    }
}

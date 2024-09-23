<?php

namespace App\Twig\Extension;

use App\Twig\Runtime\MessageRuntime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class MessageExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/3.x/advanced.html#automatic-escaping
            new TwigFilter('temps_ecoule', [MessageRuntime::class, 'tempsEcoule']),
            new TwigFilter('prestataire_sentCard', [MessageRuntime::class, 'prestataireSent_cardCss']),
            new TwigFilter('prestataire_sentBg', [MessageRuntime::class, 'prestataireSent_bgCss']),
            new TwigFilter('demandeur_sentCard', [MessageRuntime::class, 'demandeurSent_cardCss']),
            new TwigFilter('demandeur_sentBg', [MessageRuntime::class, 'demandeurSent_bgCss']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('temps_ecoule', [MessageRuntime::class, 'tempsEcoule']),
            new TwigFunction('prestataire_sentCard', [MessageRuntime::class, 'prestataireSent_cardCss']),
            new TwigFunction('prestataire_sentBg', [MessageRuntime::class, 'prestataireSent_bgCss']),
            new TwigFunction('demandeur_sentCard', [MessageRuntime::class, 'demandeurSent_cardCss']),
            new TwigFunction('demandeur_sentBg', [MessageRuntime::class, 'demandeurSent_bgCss']),
        ];
    }
}

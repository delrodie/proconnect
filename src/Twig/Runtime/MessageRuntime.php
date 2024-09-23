<?php

namespace App\Twig\Runtime;

use App\Service\Messages;
use Twig\Extension\RuntimeExtensionInterface;

class MessageRuntime implements RuntimeExtensionInterface
{
    public function __construct()
    {
        // Inject dependencies if needed
    }

    public function tempsEcoule($value)
    {
        $now = new \DateTime();
        $diff = $now->diff($value);

        if ($diff->y > 0) {
            return $diff->y . ' an' . ($diff->y > 1 ? 's' : '');
        } elseif ($diff->m > 0) {
            return $diff->m . ' mois';
        } elseif ($diff->d > 0) {
            return $diff->d . 'j';
        } elseif ($diff->h > 0) {
            return $diff->h . 'h';
        } elseif ($diff->i > 0) {
            return $diff->i . 'min';
        } elseif ($diff->s > 0) {
            return $diff->s . 's';
        } else {
            return 'à l\'instant';
        }
    }

    public function prestataireSent_cardCss($value): string
    {
        return match ($value){
            Messages::EMETTEUR_PRESTATAIRE => 'message-sent',
            Messages::EMETTEUR_DEMANDEUR => 'message-received',
            default => 'message'
        };
    }

    public function prestataireSent_bgCss($value): string
    {
        return match ($value){
            Messages::EMETTEUR_PRESTATAIRE => 'bg-main text-white',
            Messages::EMETTEUR_DEMANDEUR => 'bg-light',
            default => 'message'
        };
    }

    public function demandeurSent_cardCss($value): string
    {
        return match ($value){
            Messages::EMETTEUR_PRESTATAIRE => 'message-received',
            Messages::EMETTEUR_DEMANDEUR => 'message-sent',
            default => 'message'
        };
    }

    public function demandeurSent_bgCss($value): string
    {
        return match ($value){
            Messages::EMETTEUR_PRESTATAIRE => 'bg-light',
            Messages::EMETTEUR_DEMANDEUR => 'bg-main text-white',
            default => 'message'
        };
    }
}

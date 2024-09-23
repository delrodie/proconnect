<?php

namespace App\Twig\Runtime;

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
}

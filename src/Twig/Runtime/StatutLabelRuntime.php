<?php

namespace App\Twig\Runtime;

use Twig\Extension\RuntimeExtensionInterface;

class StatutLabelRuntime implements RuntimeExtensionInterface
{
    public function __construct()
    {
        // Inject dependencies if needed
    }

    public function statutLabel($value): string
    {
        return match($value){
            'CLOTURE' =>  'Projet clôturé',
            'TERMINE' =>  'Projet réalisé',
            'ENCOURS' => 'En réalisation',
            'DEMANDE' => 'Demande de prestation',
            default => "En appel"
        };
    }
    public function PostulerStatutLabel($value): string
    {
        return match ($value){
            'EMBAUCHE' => 'Candidature validée',
            'SOUMIS' => 'Candidature soumise',
            'DECLINE' => 'Offre déclinée',
            default => 'Candidature refusée'
        };
    }
}

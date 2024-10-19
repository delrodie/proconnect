<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Service\Utilities;
use App\Twig\Runtime\DeplacementRuntime;
use App\Twig\Runtime\ModeTravailRuntime;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class PrestataireStateProcessor implements ProcessorInterface
{
    public function __construct(private readonly Utilities $utilities, private readonly ProcessorInterface $processor, private readonly DeplacementRuntime $deplacementRuntime, private readonly ModeTravailRuntime $modeTravailRuntime)
    {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        $string = $data->getPrenoms().'-'.$data->getNom().'-'.$data->getTelephone();

        if ($uriVariables){
            $data->setSlug($this->utilities->slug($string));

            return $this->processor->process($data, $operation, $uriVariables, $context);
        }

        $slug = $this->utilities->entityExiste($string, 'prestataire');
        if (!$slug){
            throw new BadRequestHttpException("Echec, ce prestataire existe déjà.");
        }

        $data->setMatricule($this->utilities->matriculePrestataire());
        $data->setSlug($slug);
        $data->setCreatedAt($this->utilities->fuseauGMT());
        $data->setSexe($this->sexe($data->getSexe()));
        $data->setExperience((int) $data->getExperience());
        $data->setStock($this->stock($data->getStock()));
        $data->setPaiement($this->paiement($data->getPaiement()));
        $data->setDeplacement($this->deplacement($data->getDeplacement()));
        $data->setModeTravail($this->modeTravail($data->getModeTravail()));

//        dd($data);

        return $this->processor->process($data, $operation, $uriVariables, $context);
    }

    private function sexe(string $sexe): string
    {
        return match ($sexe){
            'femme', 'Femme', 'FEMME' => 'FEMME',
            default => 'HOMME'
        };
    }

    private function stock(string $stock): string
    {
        return match ($stock){
            'oui', 'Oui', 'OUI' => 'OUI',
            default => 'NON'
        };
    }

    private function paiement(string $paiement): string
    {
        return match ($paiement){
            'Espèce', 'espece' => 'ESPECE',
            'Paiement mobile', 'Mobile', 'MOBILE', 'mobile' => 'MOBILE',
            'Banque', 'banque', 'cheque', 'Chèque' => 'BANQUE',
            default => 'TOUS LES MODES'
        };
    }

    private function deplacement(string $deplacement): string
    {
        return match($deplacement){
            'Véhicule personnel', 'Personnel', 'PERSONNEL' => 'PERSONNEL',
            'Transport commun', 'Commun', 'COMMUN' => 'COMMUN',
            default => 'DEUX'
        };
    }

    private function modeTravail(string $mode): string
    {
        return match ($mode)
        {
            'Travail avec apprentis', 'Apprentis', 'apprentis' => "APPRENTIS",
            default => 'SEUL'
        };
    }
}

<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Service\Utilities;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class DemandeurStateProcessor implements ProcessorInterface
{
    public function __construct(
        private readonly Utilities $utilities,
        private ProcessorInterface $processor
    )
    {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = [])
    { //dd($data);
        $string = $data->getNom().'-'.$data->getPrenom().'-'.$data->getTelephone();
        if ($uriVariables){
            $data->setSlug($this->utilities->slug($string));

            return $this->processor->process($data, $operation, $uriVariables, $context);
        }

        $slug = $this->utilities->entityExiste($string, 'demandeur');
        if (!$slug){
            throw new BadRequestHttpException("Echèc, ces informations: {$data->getNom()} {$data->getPrenom()} et {$data->getTelephone()} existent déjà.");
        }

        $data->setCode($this->utilities->codeDemandeur());
        $data->setSlug($slug);
        $data->setCreatedAt($this->utilities->fuseauGMT());

        return $this->processor->process($data, $operation, $uriVariables, $context);
    }
}

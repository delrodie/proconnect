<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Service\Utilities;
use Doctrine\ORM\EntityManagerInterface;
use http\Exception\BadMessageException;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DomaineStateProcessor implements ProcessorInterface
{

    public function __construct(
        private readonly Utilities $utilities,
        private ProcessorInterface $processor,
    )
    {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        $slug = $this->utilities->entityExiste($data->getTitle(), 'domaine');
        if (!$slug){
            throw new BadRequestException("Echec, ce domaine a déjà été enregistré dans le système");
        }
        $data->setSlug($slug);


        return $this->processor->process($data, $operation, $uriVariables, $context);
    }
}

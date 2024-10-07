<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Service\Utilities;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

readonly class UserPasswordHasherStateProcessor implements ProcessorInterface
{

    public function __construct(private UserPasswordHasherInterface $userPasswordHasher, private ProcessorInterface $processor, private Utilities $utilities)
    {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        if (!$data->getPassword()){
            return $this->processor->process($data, $operation, $uriVariables, $context);
        }

//        if ($st)

        $hashedPassword = $this->userPasswordHasher->hashPassword(
            $data,
            $data->getPassword()
        );

        $data->setPassword($hashedPassword);
        $data->setRoles([$this->utilities->getUserRole(strtoupper($data->getStatut()))]);
        $data->setStatut(strtoupper($data->getStatut()));

        return $this->processor->process($data, $operation, $uriVariables, $context);
    }
}
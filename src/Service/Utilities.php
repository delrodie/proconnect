<?php

namespace App\Service;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

class Utilities
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserRepository $userRepository,
    )
    {
    }

    public function getUsers(string $username)
    {
        $getUsers = $this->userRepository->findWithout($username);
        $users=[];
        foreach ($getUsers as $getUser){
            $roles = $getUser->getRoles()[0] ?? $getUser->getRoles();
            $role = match ($roles) {
                'ROLE_ADMIN' => 'Administrateur',
                'ROLE_DEMANDEUR' => 'Demandeur',
                'ROLE_PRESTATAIRE' => 'Prestataire',
                default => 'Utilisateur',
            }; //dd($getUser);

            $users[] = [
                'id' => $getUser->getId(),
                'userIdentifier' => $getUser->getUsername(),
                'role' => $role,
                'connexion' => $getUser->getConnexion(),
                'lastConnectedAt' => $getUser->getLastConnectedAt(),
                'clientIp' => $getUser->getClientIp()
            ];
        }

        return $users;
    }

    public function getUserRole($statut): string
    {
        return match ($statut){
            'DEMANDEUR' => 'ROLE_DEMANDEUR',
            'PRESTATAIRE' => 'ROLE_PRESTATAIRE',
            default => 'ROLE_USER'
        };
    }
}
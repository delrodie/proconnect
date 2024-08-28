<?php

namespace App\EventSubscriber;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Event\AuthenticationSuccessEvent;

class AuthenticateSubscriber implements EventSubscriberInterface
{

    public function __construct(
        private UserRepository $userRepository,
        private EntityManagerInterface $entityManager,
        private RequestStack $requestStack,
    )
    {
    }

    public function onSecurityAuthenticationSuccess(AuthenticationSuccessEvent $event): void
    {
        $securityToken = $event->getAuthenticationToken();
        $userIdentity = $this->getUserIdentity($securityToken);

        // Mise a jour de la ligne utilisateur
        $userEntity = $this->userRepository->findOneBy(['username'=> $userIdentity]);
        if ($userEntity){
            $userEntity
                ->setConnexion((int) $userEntity->getConnexion() + 1)
                ->setLastConnectedAt(new \DateTime())
                ->setClientIp($this->requestStack->getCurrentRequest()->getClientIp())
            ;

            $this->entityManager->flush();
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'security.authentication.success' => 'onSecurityAuthenticationSuccess',
        ];
    }

    private function getUserIdentity(TokenInterface $securityToken): string
    {
        return $securityToken->getUserIdentifier();
    }
}

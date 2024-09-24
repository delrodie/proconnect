<?php

namespace App\Service;

use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;

class RedirectPath
{
    public function __construct(
        private RouterInterface $router,
        private AllRepositories $allRepositories,
        private Security $security,
        private RequestStack $requestStack,
    )
    {
    }

    public function redirectNotDemandeur($codeDemandeur): false|string
    {
        // Recuperer l'historique de la session
        $request = $this->requestStack->getCurrentRequest();
        $referer = $request->headers->get('referer');

        $demandeur = $this->allRepositories->getOneDemandeur(null, $this->security->getUser());
        if (!$demandeur|| $demandeur->getCode() !== $codeDemandeur){
            sweetalert()->error(Messages::DEMANDEUR_NOT_YOU,[],"Erreur d'authentification");
            return $referer;
        }

        return false;
    }
}
<?php

namespace App\EventListener;

use ApiPlatform\Symfony\Security\Exception\AccessDeniedException;
use App\Service\ApiRepositories;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\KernelEvents;

final class ApiRequestListener
{
    public function __construct(private readonly ApiRepositories $apiRepositories)
    {
    }

    #[AsEventListener(event: KernelEvents::REQUEST)]
    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest(); //dd($request);

        $pathInfo =$request->getPathInfo();

        if (str_starts_with($pathInfo, '/api')){
            if (str_starts_with($pathInfo, '/api/docs') || str_starts_with($pathInfo, '/api/login')){
                return;
            }

            $apiKey = $request->headers->get('X-API-KEY');
            if (!$apiKey){
                throw new AccessDeniedHttpException("Le clé API manque dans votre requête");
            }

            // Verification de la cle de l'API
            $apiClient = $this->apiRepositories->getApiKey($apiKey);
            if (!$apiClient){
                throw new AccessDeniedHttpException("Votre clé API n'est pas valide. Merci de contacter les administrateurs");
            }
        }
    }
}

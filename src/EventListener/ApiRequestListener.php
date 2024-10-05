<?php

namespace App\EventListener;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

final class ApiRequestListener
{
    #[AsEventListener(event: KernelEvents::REQUEST)]
    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();

        if (str_starts_with($request->getPathInfo(), '/api')){
//            $request->getSession()->invalidate();
            if ($request->hasSession()) {
//                $request->getSession()->invalidate();
                // Ou utilisez cette ligne si vous voulez marquer la session comme "stateless"
//                 $request->getSession()->set('is_stateless', true);
            }
        }
    }
}

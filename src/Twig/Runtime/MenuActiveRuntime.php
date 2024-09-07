<?php

namespace App\Twig\Runtime;

use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Extension\RuntimeExtensionInterface;

class MenuActiveRuntime implements RuntimeExtensionInterface
{
    public function __construct(
        private RequestStack $requestStack
    )
    {
        // Inject dependencies if needed
    }

    public function getActiveClass($value): string
    {
        $currentRoute = $this->requestStack->getCurrentRequest()->attributes->get('_route');

        return $currentRoute === $value ? 'active' : '';
    }
}

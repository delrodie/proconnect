<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class LoginRedirectPath
{
    use TargetPathTrait;

    public function __construct(
        private RouterInterface $router,
        private RequestStack $requestStack
    )
    {
    }

    /**
     * URL de redirection de l'utilisateur après inscription
     *  Selon son profile
     * @param string $statut
     * @return void
     */
    public function getRedirectUrlBaseOnRole(string $statut): void
    {
        $targetPath = $this->getPath($statut);

        $this->saveTargetPath($this->requestStack->getSession(), 'main', $targetPath);
    }

    public function redirectAfterLogin($user): string
    {
        $statut = $user->getStatut();
        $role = $user->getRoles()[0];
//        $test=0;

        if (!$statut) {
            if ($role === 'ROLE_ADMIN' || $role === 'ROLE_SUPER_ADMIN') {
                $statut = 'ADMIN';
            }
        }
        return $this->getPath($statut);
    }

    /**
     * @param $statut
     * @return string
     */
    protected function getPath($statut): string
    {
        if ($statut === 'DEMANDEUR'){
            $targetPath =  $this->router->generate('app_frontend_demandeur_tbord');
        } elseif ($statut === 'PRESTATAIRE'){
            $targetPath = $this->router->generate('app_home_index');
        }elseif ($statut === 'ADMIN'){
            $targetPath = $this->router->generate('app_backend_dashboard');
        }else{
            $targetPath = $this->router->generate('app_home_index');
        }

        return $targetPath;
    }

}
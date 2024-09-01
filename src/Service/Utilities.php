<?php

namespace App\Service;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use Symfony\Component\String\AbstractUnicodeString;
use Symfony\Component\String\Slugger\AsciiSlugger;

class Utilities
{
    use TargetPathTrait;
//    use Securi

    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserRepository $userRepository,
        private RouterInterface $router,
        private RequestStack $requestStack,
        private AllRepositories $allRepositories,
    )
    {
    }

    /**
     * Formattage de l'heure des actions
     * selon le fuseau horaire GMT 24081440
     */
    public function fuseauGMT(): \DateTime
    {
        // Definitions l'heure actuelle selon le fuseau horaire GMT
        $fuseauGMT = new \DateTimeZone('GMT');
        return (new \DateTime('now', $fuseauGMT));
    }

    /**
     * Formattage du slug
     *
     * @param string $string
     * @return \Symfony\Component\String\AbstractUnicodeString
     */
    public function slug(string $string): AbstractUnicodeString
    {
        return (new AsciiSlugger())->slug(strtolower($string));
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

    /**
     * Generation du code du demandeur
     *
     * @param $username
     * @return string
     * @throws \Random\RandomException
     */
    public function codeDemandeur(): string
    {
        do{
            $nombreAleatory = random_int(1000,9999);
            $codeDate = date('ym');
            $code = $codeDate.''.$nombreAleatory;

            $verify = $this->allRepositories->getOneDemandeur($code);
        } while($verify);

        return $code;
    }

    public function entityExiste(string $string, string $entity): AbstractUnicodeString|false
    {
        $slug = $this->slug($string);

        $verif = match ($entity){
            'domaine' => $this->allRepositories->getOneDomaine($slug),
            'categorie' => $this->allRepositories->getOneCategorie($slug),
            default => ""
        };

//        if ($entity === 'domaine') {
//            $verif = $this->allRepositories->getOneDomaine($slug);
//        }

        if ($verif) return false;

        return $slug;
    }
}
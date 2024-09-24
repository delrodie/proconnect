<?php

namespace App\Service;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Random\RandomException;
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
    const PROJET_REALISE = 'TERMINE';
    const PROJET_ENCOURS = 'ENCOURS';
    const PROJET_APPEL = 'APPEL';
    const PROJET_DEMANDE = "DEMANDE";
    const POSTULER_EMBAUCHE = 'EMBAUCHE';
    const POSTULER_SOUMIS = 'SOUMIS';
    const POSTULER_ACCEPTE = 'ACCEPTE';
    const POSTULER_DECLINE = 'DECLINE';

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
     * @return string
     * @throws RandomException
     */
    public function codeDemandeur(): string
    {
        do{
            $nombreAleatory = random_int(1000,9999);
            $codeDate = date('ym');
            $code = $codeDate.$nombreAleatory;

            $verify = $this->allRepositories->getOneDemandeur($code);
        } while($verify);

        return $code;
    }

    public function matriculePrestataire(): string
    {
        do{
            $nombreAleatoire = random_int(1000,9999);
            $codeDate = date('ymd');
            $matricule = $codeDate.$nombreAleatoire;

            $verify = $this->allRepositories->getOnePrestataire($matricule);
        } while($verify);

        return $matricule;
    }

    /**
     * Generation de la reference du projet
     *
     * @return string
     * @throws RandomException
     */
    public function referenceProjet(): string
    {
        do{
            $nombreAleatory = random_int(1000,9999);
            $code = date('ymd');
            $reference = $code.$nombreAleatory;

            $verify = $this->allRepositories->getOneProjet($reference);
        }while($verify);

        return $reference;
    }

    /**
     * Generation de la reference
     *
     * @return string
     * @throws RandomException
     */
    public function referencePostuler(): string
    {
        do{
            $nombreAleatory = random_int(100,999);
            $code = date('ymd');
            $reference = $code.$nombreAleatory;

            $verify = $this->allRepositories->getOnePostuler($reference);
        }while($verify);

        return $reference;
    }

    public function referenceMessage(): int
    {
        return time();
    }

    public function entityExiste(string $string, string $entity): AbstractUnicodeString|false
    {
        $slug = $this->slug($string);

        $verif = match ($entity){
            'domaine' => $this->allRepositories->getOneDomaine($slug),
            'categorie' => $this->allRepositories->getOneCategorie($slug),
            'competence' => $this->allRepositories->getOneCompetence($slug),
            'localite' => $this->allRepositories->getOneLocalite($slug),
            default => ""
        };

//        if ($entity === 'domaine') {
//            $verif = $this->allRepositories->getOneDomaine($slug);
//        }

        if ($verif) return false;

        return $slug;
    }

    public function stickCssClass($statut): string
    {
        return match ($statut){
            'TERMINE' => 'text-bg-success',
            'ENCOURS' => 'text-bg-warning',
            default => 'text-bg-info'
        };
    }

    public function typeOfUser($user): false|int
    {
        $userTypes = [
            1 => 'getOneDemandeur',
            2 => 'getOnePrestataire',
        ];

        foreach ($userTypes as $type => $method) {
            if ($this->allRepositories->$method(null, $user)) {
                return $type;
            }
        }

        return false;
    }

    public function messageAlert($code): string
    {
        return match ($code){
            201 => "Attention, vous avez déjà postulé à ce projet. Veuillez vous rendre dans votre compte pour modifier votre offre!",
            202 => "La modification de votre offre a été effectuée avec succès!",
            203 => "La suppression de votre offre a été effectuée avec succès!",
            401 => "Vous n'êtes pas autorisé à soumettre d'offre de prestation. Cette section est réservée exclusivement aux prestataires.",
            default => "Erreur"
        };
    }



}
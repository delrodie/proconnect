<?php

namespace App\Service;

use App\Repository\CategorieRepository;
use App\Repository\CompetenceRepository;
use App\Repository\DemandeurRepository;
use App\Repository\DomaineRepository;
use App\Repository\MaintenanceRepository;
use App\Repository\PostulerRepository;
use App\Repository\PrestataireRepository;
use App\Repository\ProjetRepository;

class AllRepositories
{
    public function __construct(
        private MaintenanceRepository $maintenanceRepository,
        private DemandeurRepository   $demandeurRepository,
        private DomaineRepository     $domaineRepository,
        private CategorieRepository   $categorieRepository,
        private ProjetRepository      $projetRepository,
        private CompetenceRepository  $competenceRepository,
        private PrestataireRepository $prestataireRepository,
        private PostulerRepository    $postulerRepository,
    )
    {
    }

    public function isMaintenance()
    {
        $maintenance = $this->maintenanceRepository->findOneBy(['active' => true]);
        if ($maintenance){
            return $maintenance;
        }

        return false;
    }

    public function getOneDemandeur(string $code = null, object $user = null)
    {
        if ($code){
            return $this->demandeurRepository->findOneBy(['code' => $code]);
        }

        if ($user){
            return $this->demandeurRepository->findOneBy(['user' => $user]);
        }

        return $this->demandeurRepository->findOneBy([], ['id' => 'DESC']);
    }

    public function getOneDomaine(string $slug = null)
    {
        if ($slug){
            return $this->domaineRepository->findOneBy(['slug' => $slug]);
        }

        return $this->domaineRepository->findOneBy([], ['id' => "DESC"]);
    }

    public function getOneCategorie(string $slug = null)
    {
        if ($slug){
            return $this->categorieRepository->findOneBy(['slug' => $slug]);
        }

        return $this->categorieRepository->findOneBy([], ['id' => "DESC"]);
    }

    public function getOneProjet(string $reference = null)
    {
        if ($reference){
            return $this->projetRepository->findOneBy(['reference' => $reference]);
        }

        return $this->projetRepository->findOneBy([],['id' => "DESC"]);
    }

    public function getOneCompetence(string $slug = null )
    {
        if ($slug) return $this->competenceRepository->findOneBy(['slug' => $slug]);

        return $this->competenceRepository->findOneBy([],['id' => 'DESC']);
    }

    public function getOnePrestataire(string $matricule = null, object $user = null)
    {
        if ($matricule) return $this->prestataireRepository->findOneBy(['matricule' => $matricule]);

        if ($user) return $this->prestataireRepository->findOneBy(['user' => $user]);

        return $this->prestataireRepository->findOneBy([], ['id' => 'DESC']);
    }

    public function getOnePostuler(string $reference = null, object $user = null)
    {
        if ($reference) return $this->postulerRepository->findOneBy(['reference' => $reference]);

        if ($user) return $this->postulerRepository->findOneBy(['user' => $user]);

        return $this->postulerRepository->findOneBy([],['id' =>'DESC']);
    }

    public function findProjetsByUser($user)
    {
        return $this->projetRepository->findBy(['user' => $user], ['createdAt' =>'DESC']);
    }

    public function findAllProjets(): array
    {
        return $this->foreachProjets($this->projetRepository->findAll());
    }

    public function findAllProjetByStatut(string $statut = null, $date=null, $budget=null): array
    {
        return $this->foreachProjets($this->projetRepository->findAllByStatut($statut, $date, $budget));
    }

    public function backendStatut($statut): string
    {
        return match ($statut){
            'TERMINE' => 'projet-realise',
            'ENCOURS' => 'en-realisation',
            default => 'en-appel'
        };
    }

    public function frontendStatut($statut): string
    {
        return match($statut){
            'TERMINE' =>  'Projet réalisé',
            'ENCOURS' => 'En réalisation',
            default => "En appel"
        };
    }

    public function getDemandeurByUser($user)
    {
        return $this->demandeurRepository->findOneBy(['user' => $user]);
    }

    public function getProjetByUser($user)
    {
        return $this->projetRepository->findBy(['user' => $user], ['id' => 'DESC']);
    }

    public function findPostulerByReferenceAndUser(object $projet, object $user)
    {
        return $this->postulerRepository->findOneBy([
            'projet' => $projet,
            'user' => $user
        ]);
    }

    public function findPostulerByUser(object $user)
    {
        return $this->postulerRepository->findBy(['user' => $user], ['id' => 'DESC']);
    }

    public function foreachProjets($projets): array
    {
        $results=[];
        $i=0;

        foreach ($projets as $projet){
            $results[$i++] = [
                'categorie' => $projet->getCategorie()->getTitle(),
                'user' => $projet->getUser()->getUsername(),
                'id' => $projet->getId(),
                'reference' => $projet->getReference(),
                'title' => $projet->getTitle(),
                'lieu' => $projet->getLieu(),
                'date_presentation' => $projet->getDatePrestation(),
                'date_limite' => $projet->getDateLimite(),
                'preference' => $projet->getPreference(),
                'budget_min' => $projet->getBudgetMin(),
                'budget_max' => $projet->getBudgetMax(),
                'description' => $projet->getDescription(),
                'created_at' => $projet->getCreatedAt(),
                'statut' => $projet->getStatut(),
                'statut_backend' => $this->backendStatut($projet->getStatut()),
                'demandeur' => $this->getDemandeurByUser($projet->getUser())
            ];
        }

        return $results;
    }
}
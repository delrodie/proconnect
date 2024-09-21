<?php

namespace App\Service;

use App\Repository\CallToActionRepository;
use App\Repository\CategorieRepository;
use App\Repository\CompetenceRepository;
use App\Repository\DemandeurRepository;
use App\Repository\DomaineRepository;
use App\Repository\LocaliteRepository;
use App\Repository\MaintenanceRepository;
use App\Repository\ParallaxRepository;
use App\Repository\PartenaireRepository;
use App\Repository\PostulerRepository;
use App\Repository\PrestataireRepository;
use App\Repository\ProjetRepository;
use App\Repository\SlideRepository;

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
        private LocaliteRepository $localiteRepository,
        private SlideRepository $slideRepository,
        private PartenaireRepository $partenaireRepository,
        private CallToActionRepository $callToActionRepository,
        private ParallaxRepository $parallaxRepository
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

    public function getOneLocalite(string $slug = null)
    {
        return $this->localiteRepository->findOneBy(['slug' => $slug]);
    }

    public function getOneSlide()
    {
        return $this->slideRepository->findOneBy(['statut' => true],['id' => 'DESC']);
    }

    public function getOneCallToAction(string $type = null)
    {
        if ($type) return $this->callToActionRepository->findOneBy(['type' => $type, 'statut' => true], ['id' => 'DESC']);

        return $this->callToActionRepository->findOneBy(['statut' => true], ['id' => 'DESC']);
    }

    public function getOneParallax()
    {
        return $this->parallaxRepository->findOneBy(['statut' => true], ['id' => 'DESC']);
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

    public function findProjetByCategorie($category)
    {
        return $this->foreachProjets($this->projetRepository->findBy(['categorie' => $category], ['dateLimite' => 'DESC']));
    }

    public function findCanditatureByProjet($reference)
    {
        return $this->candidature($this->postulerRepository->getCanditatureByProjet($reference));
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

    public function getProjetDetails($reference)
    {
        return $this->projetRepository->findDetailsProjetByReference($reference);
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

    public function getOnePostulerByReference($reference)
    {
        return $this->postulerRepository->findOneByReference($reference);
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
                'demandeur' => $this->getDemandeurByUser($projet->getUser()),
                'localite' => $projet->getLocalite()->getTitle()
            ];
        }

        return $results;
    }

    public function candidature($candidatures): array
    {
        $candidats = []; $i=0;
        foreach ($candidatures as $candidature) {
            $prestataire = $this->getOnePrestataire(null, $candidature->getUser());
            $candidats[$i++] = [
                'id' => $candidature->getId(),
                'reference' => $candidature->getReference(),
                'facturation' => $candidature->getFacturation(),
                'modeTravail' => $candidature->getModeTravail(),
                'garantie' => $candidature->getGarantie(),
                'delai' => $candidature->getDelai(),
                'statut' => $candidature->getStatut(),
                'createdAt' => $candidature->getCreatedAt(),
                'description' => $candidature->getDescription(),
                'projet_id' => $candidature->getProjet()->getId(),
                'projet_reference' => $candidature->getProjet()->getReference(),
                'projet_title' => $candidature->getProjet()->getTitle(),
                'projet_lieu' => $candidature->getProjet()->getLieu(),
                'projet_datePrestation' => $candidature->getProjet()->getDatePrestation(),
                'projet_dateLimite' => $candidature->getProjet()->getDateLimite(),
                'projet_preference' => $candidature->getProjet()->getPreference(),
                'projet_budgetMin' => $candidature->getProjet()->getBudgetMin(),
                'projet_budgetMax' => $candidature->getProjet()->getBudgetMax(),
                'projet_description' => $candidature->getProjet()->getDescription(),
                'projet_media' => $candidature->getProjet()->getMedia(),
                'projet_statut' => $candidature->getProjet()->getStatut(),
                'projet_createdAt' => $candidature->getProjet()->getCreatedAt(),
                'user' => $candidature->getUser()->getUsername(),
                'prestataire_matricule' => $prestataire->getMatricule(),
                'prestataire_nom' => $prestataire->getNom(),
                'prestataire_prenoms' => $prestataire->getPrenoms(),
                'prestataire_media' => $prestataire->getMedia()
            ];
        }

        return $candidats;
    }

    public function getPrestataireByOffre($offre)
    {
        $postuler = $this->postulerRepository->findByProjetAndStatutDifferentOfAppel($offre);
        $prestataire = [];
        if ($postuler){
            $prestataire = $this->getOnePrestataire(null, $postuler->getUser());
        }

        return $prestataire;
    }

    public function getCandidatureValideByProjet($projet)
    {
        return $this->postulerRepository->findByProjetAndStatutDifferentOfAppel($projet);
    }

    public function getCandidatureValideByPrestataire($user)
    {
        return $this->postulerRepository->findValideByPrestataire($user);
    }

    /**
     * Liste de tous les domaines
     */
    public function getAllDomaine()
    {
        return $this->domaineRepository->findAll();
    }

    /**
     * Liste de toutes les localités
     */
    public function getAllLocalite()
    {
        return $this->localiteRepository->findAll();
    }

    public function getAllPartenaire()
    {
        return $this->partenaireRepository->findAll();
    }

    public function getAllPrestataire()
    {
        return $this->prestataireRepository->findAllPrestataire();
    }

    public function getPrestataireByMatricule($matricule)
    {
        return $this->prestataireRepository->findByMatricule($matricule);
    }
}
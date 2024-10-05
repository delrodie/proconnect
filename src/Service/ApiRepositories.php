<?php

namespace App\Service;

use App\Entity\Domaine;
use App\Repository\CategorieRepository;
use App\Repository\DemandeurRepository;
use App\Repository\DomaineRepository;
use App\Repository\MessageRepository;
use App\Repository\PartenaireRepository;
use App\Repository\PrestataireRepository;
use App\Repository\ProjetRepository;
use App\Service\GestionMedia;
use App\Twig\Runtime\DeplacementRuntime;
use App\Twig\Runtime\ExperienceRuntime;
use App\Twig\Runtime\ModeTravailRuntime;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ApiRepositories
{
    const PARTENAIRE_DIRECTORY = 'partenaire';
    const DEMANDEUR_DIRECTORY = 'demandeur';
    const PRESTATAIRE_DIRECTORY = 'prestataire';
    const PROJET_DIRECTORY = 'projets';
    const PRESTATAIRE_DOCUMENT_DIRECTORY = 'prestataire/document';
    const PRESTATAIRE_LICENCE_DIRECTORY = 'prestataire/licence';

    public function __construct(
        private readonly PartenaireRepository  $partenaireRepository,
        private UrlGeneratorInterface          $urlGenerator,
        private readonly DemandeurRepository   $demandeurRepository,
        private readonly MessageRepository     $messageRepository,
        private readonly PrestataireRepository $prestataireRepository,
        private readonly ExperienceRuntime     $experienceRuntime,
        private readonly DeplacementRuntime    $deplacementRuntime,
        private readonly ModeTravailRuntime    $modeTravailRuntime, private readonly ProjetRepository $projetRepository, private readonly DomaineRepository $domaineRepository, private readonly CategorieRepository $categorieRepository
    )
    {
    }

    /**
     * Liste des partenaires
     *
     * @return array
     */
    public function getListPartenaires(): array
    {
        $partenaires = $this->partenaireRepository->findAll();
        $result = []; $i=0;

        foreach ($partenaires as $partenaire) {
            $result[$i++] = [
                'id' => $partenaire->getId(),
                'nom' => $partenaire->getNom(),
                'media' => $this->generateMediaUrl($partenaire->getMedia(), self::PARTENAIRE_DIRECTORY)
            ];
        }

        return $result;
    }

    public function getShowPartenaire($id)
    {
        return $this->partenaireRepository->findOneBy(['id' => $id]);
    }

    /******
     * LES DEMANDEURS
     */

    /**
     * Liste des demandeurs
     * 
     * @return array
     */
    public function getListDemandeur(): array
    {
        $demandeurs = $this->demandeurRepository->findAllDemandeur();
        $result = []; $i=0;

        foreach ($demandeurs as $demandeur) {
            $result[$i++] = $this->demandeurArray($demandeur);
        }

        return $result;
    }

    /**
     * Le profile du demandeur
     *
     * @param $demandeur
     * @return array
     */
    public function showDemandeur($demandeur): array
    {
        return $this->demandeurArray($demandeur);
    }

    /**
     * @param $demandeur
     * @return array
     */
    private function demandeurArray($demandeur): array
    {
        return [
            'id' => $demandeur->getId(),
            'code' => $demandeur->getCode(),
            'nom' => $demandeur->getNom(),
            'prenom' => $demandeur->getPrenom(),
            'email' => $demandeur->getEmail(),
            'telephone' => $demandeur->getTelephone(),
            'profession' => $demandeur->getProfession(),
            'adresse' => $demandeur->getAdresse(),
            'slug' => $demandeur->getSlug(),
            'media' => $this->generateMediaUrl($demandeur->getMedia(), self::DEMANDEUR_DIRECTORY),
            'createdAt' => $demandeur->getCreatedAt() ? $demandeur->getCreatedAt()->format('Y-m-d H:i:s') : null,
            'localite' => $demandeur->getLocalite(),
        ];
    }

    /******
     * LES MESSAGES
     */

    /**
     * Liste des messages du demandeur
     *
     * @param $demandeur
     * @return array
     */
    public function getMessagesByDemandeur($demandeur): array
    {
        $messages = $this->messageRepository->findByDemandeur($demandeur->getCode());
        $result =[]; $i=0;

        foreach ($messages as $message) {
            $result[$i++]=[
                'id' => $message->getId(),
                'reference' => $message->getReference(),
                'content' => $message->getContent(),
                'createdAt' => $message->getCreatedAt()->format('Y-m-d H:i:s'),
                'emetteur' => $message->getEmetteur(),
                'vue' => $message->isVue(),
                'vueAt' => $message->getVueAt(),
                'demandeur_nom' => $message->getDemandeur()->getNom(),
                'demandeur_prenom' => $message->getDemandeur()->getPrenom(),
                'demandeur_code' => $message->getDemandeur()->getCode(),
                'demandeur_media' => $this->generateMediaUrl($message->getDemandeur()->getMedia(), self::DEMANDEUR_DIRECTORY),
                'prestataire_nom' => $message->getPrestataire()->getNom(),
                'prestataire_prenom' => $message->getPrestataire()->getPrenoms(),
                'prestataire_matricule' => $message->getPrestataire()->getMatricule(),
                'prestataire_media' => $this->generateMediaUrl($message->getPrestataire()->getMedia(), self::PRESTATAIRE_DIRECTORY),
            ];
        }

        return $result;
    }

    public function getListPrestataire(): array
    {
        $prestataires = $this->prestataireRepository->findAllPrestataire();
        $result=[]; $i=0;

        foreach ($prestataires as $prestataire) {
            $result[$i++] = $this->prestataireArray($prestataire);
        }

        return $result;
    }

    /**
     * @param object $prestataire
     * @return array
     */
    public function showPrestataire(object $prestataire): array
    {
        return $this->prestataireArray($prestataire);
    }

    private function prestataireArray($prestataire): array
    {
        $competence=[];
        return [
            'id' => $prestataire->getId(),
            'matricule' => $prestataire->getMatricule(),
            'nom' => $prestataire->getNom(),
            'prenom' => $prestataire->getPrenoms(),
            'sexe' => $prestataire->getSexe(),
            'profession' => $prestataire->getProfession(),
            'email' => $prestataire->getEmail(),
            'telephone' => $prestataire->getTelephone(),
            'media' => $this->generateMediaUrl($prestataire->getMedia(), self::PRESTATAIRE_DIRECTORY),
            'localite' => $prestataire->getLocalite()->getTitle(),
            'geolocalisation' => $prestataire->getGeolocalisation(),
            'niveau' => $prestataire->getNiveau(),
            'experience' => $this->experienceRuntime->getExperience($prestataire->getExperience()),
            'langue' => $prestataire->getLangue(),
            'tarif_horaire' => $prestataire->getTarifHoraire(),
            'stock' => $prestataire->getStock(),
            'paiement' => $prestataire->getPaiement(),
            'garantie' => $prestataire->getGarantie(),
            'deplacement' => $this->deplacementRuntime->modeDeplacement($prestataire->getDeplacement()),
            'competence' => $prestataire->getCompetence(),
            'casier_judiciaire' => $this->generateMediaUrl($prestataire->getCasier(), self::PRESTATAIRE_DOCUMENT_DIRECTORY),
            'licence' => $this->generateMediaUrl($prestataire->getLicence(), self::PRESTATAIRE_LICENCE_DIRECTORY),
            'created_at' => $prestataire->getCreatedAt() ? $prestataire->getCreatedAt()->format('Y-m-d H:i:s') : null,
            'biographie' => $prestataire->getBiographie(),
            'slug' => $prestataire->getSlug(),
            'mode_travail' => $this->modeTravailRuntime->modeTravail($prestataire->getModeTravail())
        ];
    }

    /**
     * PROJET
     */

    public function getListProjet()
    {
        return $this->projetRepository->findAllProjets();
    }

    /**
     * Generation du lien deu logo du partenaire
     *
     * @param string|null $mediaFileName
     * @return string
     */
    public function generateMediaUrl(?string $mediaFileName, ?string $file): string
    {
        $url = $this->urlGenerator->generate('app_home_index',[],UrlGeneratorInterface::ABSOLUTE_URL);

        return "{$url}upload/{$file}/{$mediaFileName}";
    }

    public function showDomaine($domaineId)
    {
        $categories = $this->categorieRepository->findByDomaine($domaineId);
        $domaine =  $this->domaineRepository->findOneBy(['id' => $domaineId]);

        return [
            'id' => $domaine->getId(),
            'title' => $domaine->getTitle(),
            'slug' => $domaine->getSlug(),
            'categories' => $categories
        ];
    }
}
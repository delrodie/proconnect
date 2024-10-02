<?php

namespace App\Service;

use App\Repository\DemandeurRepository;
use App\Repository\MessageRepository;
use App\Repository\PartenaireRepository;
use App\Service\GestionMedia;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ApiRepositories
{
    const PARTENAIRE_DIRECTORY = 'partenaire';
    const DEMANDEUR_DIRECTORY = 'demandeur';
    const PRESTATAIRE_DIRECTORY = 'prestataire';
    const PROJET_DIRECTORY = 'projets';

    public function __construct(
        private readonly PartenaireRepository $partenaireRepository,
        private UrlGeneratorInterface         $urlGenerator, private readonly DemandeurRepository $demandeurRepository, private readonly MessageRepository $messageRepository,
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

    /**
     * Generation du lien deu logo du partenaire
     *
     * @param string|null $mediaFileName
     * @return string
     */
    private function generateMediaUrl(?string $mediaFileName, ?string $file): string
    {
        $url = $this->urlGenerator->generate('app_home_index',[],UrlGeneratorInterface::ABSOLUTE_URL);

        return "{$url}upload/{$file}/{$mediaFileName}";
    }
}
<?php

namespace App\Service;

use App\Repository\PartenaireRepository;
use App\Service\GestionMedia;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ApiRepositories
{
    public function __construct(
        private readonly PartenaireRepository $partenaireRepository,
        private UrlGeneratorInterface $urlGenerator,
    )
    {
    }

    public function getListPartenaires()
    {
        $partenaires = $this->partenaireRepository->findAll();
        $result = []; $i=0;

        foreach ($partenaires as $partenaire) {
            $result[$i++] = [
                'id' => $partenaire->getId(),
                'nom' => $partenaire->getNom(),
                'media' => $this->partenaireMediaUrl($partenaire->getMedia())
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
    private function partenaireMediaUrl(?string $mediaFileName): string
    {
        $url = $this->urlGenerator->generate('app_home_index',[],UrlGeneratorInterface::ABSOLUTE_URL);

        return "{$url}upload/partenaire/{$mediaFileName}";
    }
}
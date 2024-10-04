<?php

namespace App\DTO;

use Symfony\Component\Serializer\Annotation\Groups;

class PartenaireOutput
{
    #[Groups(['partenaire.list'])]
    private ?int $id = null;

    #[Groups(['partenaire.list'])]
    private ?string $nom = null ;

    #[Groups(['partenaire.list'])]
    private ?string $media = null;

    #[Groups(['partenaire.list'])]
    private ?string $mediaUrl = null;

    public function __construct(?int $id, ?string $nom,?string $media)
    {
        $this->id = $id;
        $this->nom = $nom;
        $this->media = $media;
//        $this->mediaUrl = $mediaUrl;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function getMedia(): ?string
    {
        return $this->media;
    }

    public function getMediaUrl(): ?string
    {
        return $this->mediaUrl;
    }
}
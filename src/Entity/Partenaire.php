<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use App\DTO\PartenaireOutput;
use App\Repository\PartenaireRepository;
use App\State\PartenaireStateProvider;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: PartenaireRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(normalizationContext: ['groups' => ['partenaire.list']])
    ],
    formats: ['json' => ['application/json'], 'ld+json' => ['application/ld+json']],
    denormalizationContext: ['groups' => ['partenaire.write']],
    paginationEnabled: false,
    provider: PartenaireStateProvider::class
)]
class Partenaire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['partenaire.list'])]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['partenaire.list'])]
    private ?string $nom = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['partenaire.list'])]
    private ?string $media = null;

    #[Groups(['partenaire.list'])]
    private ?string $mediaUrl = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getMedia(): ?string
    {
        return $this->media;
    }

    public function setMedia(?string $media): static
    {
        $this->media = $media;

        return $this;
    }

    public function getMediaUrl(): ?string
    {
        return $this->mediaUrl;
    }

    public function setMediaUrl(?string $mediaUrl): void
    {
        $this->mediaUrl = $mediaUrl;
    }
}

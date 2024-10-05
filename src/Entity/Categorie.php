<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use App\Repository\CategorieRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ApiResource(
    operations: [
        new GetCollection(normalizationContext: ['groups' => ['categorie.list']])
    ],
    formats: ['json' => ['application/json'], 'ld+json' => ['application/ld+json']],
    paginationEnabled: false
)]
#[ORM\Entity(repositoryClass: CategorieRepository::class)]
class Categorie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['categorie.list'])]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['domaine.show', 'categorie.list', 'projet.list'])]
    private ?string $Title = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['categorie.list'])]
    private ?string $slug = null;

    #[ORM\ManyToOne(inversedBy: 'categories')]
    #[Groups(['categorie.list'])]
    private ?Domaine $domaine = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->Title;
    }

    public function setTitle(?string $Title): static
    {
        $this->Title = $Title;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    public function getDomaine(): ?Domaine
    {
        return $this->domaine;
    }

    public function setDomaine(?Domaine $domaine): static
    {
        $this->domaine = $domaine;

        return $this;
    }
}

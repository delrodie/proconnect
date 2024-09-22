<?php

namespace App\Entity;

use App\Repository\ProjetRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProjetRepository::class)]
class Projet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $reference = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $title = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $lieu = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $datePrestation = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateLimite = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $preference = null;

    #[ORM\Column(nullable: true)]
    private ?int $budgetMin = null;

    #[ORM\Column(nullable: true)]
    private ?int $budgetMax = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $media = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $statut = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\ManyToOne]
    private ?User $user = null;

    #[ORM\ManyToOne]
    private ?Categorie $categorie = null;

    /**
     * @var Collection<int, Postuler>
     */
    #[ORM\OneToMany(targetEntity: Postuler::class, mappedBy: 'projet')]
    private Collection $postulers;

    #[ORM\ManyToOne]
    private ?Localite $localite = null;

    /**
     * @var Collection<int, ProjetImage>
     */
    #[ORM\OneToMany(targetEntity: ProjetImage::class, mappedBy: 'projet')]
    private Collection $projetImages;

    public function __construct()
    {
        $this->postulers = new ArrayCollection();
        $this->projetImages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(?string $reference): static
    {
        $this->reference = $reference;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getLieu(): ?string
    {
        return $this->lieu;
    }

    public function setLieu(?string $lieu): static
    {
        $this->lieu = $lieu;

        return $this;
    }

    public function getDatePrestation(): ?\DateTimeInterface
    {
        return $this->datePrestation;
    }

    public function setDatePrestation(?\DateTimeInterface $datePrestation): static
    {
        $this->datePrestation = $datePrestation;

        return $this;
    }

    public function getDateLimite(): ?\DateTimeInterface
    {
        return $this->dateLimite;
    }

    public function setDateLimite(?\DateTimeInterface $dateLimite): static
    {
        $this->dateLimite = $dateLimite;

        return $this;
    }

    public function getPreference(): ?string
    {
        return $this->preference;
    }

    public function setPreference(?string $preference): static
    {
        $this->preference = $preference;

        return $this;
    }

    public function getBudgetMin(): ?int
    {
        return $this->budgetMin;
    }

    public function setBudgetMin(?int $budgetMin): static
    {
        $this->budgetMin = $budgetMin;

        return $this;
    }

    public function getBudgetMax(): ?int
    {
        return $this->budgetMax;
    }

    public function setBudgetMax(?int $budgetMax): static
    {
        $this->budgetMax = $budgetMax;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

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

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(?string $statut): static
    {
        $this->statut = $statut;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): static
    {
        $this->categorie = $categorie;

        return $this;
    }

    /**
     * @return Collection<int, Postuler>
     */
    public function getPostulers(): Collection
    {
        return $this->postulers;
    }

    public function addPostuler(Postuler $postuler): static
    {
        if (!$this->postulers->contains($postuler)) {
            $this->postulers->add($postuler);
            $postuler->setProjet($this);
        }

        return $this;
    }

    public function removePostuler(Postuler $postuler): static
    {
        if ($this->postulers->removeElement($postuler)) {
            // set the owning side to null (unless already changed)
            if ($postuler->getProjet() === $this) {
                $postuler->setProjet(null);
            }
        }

        return $this;
    }

    public function getLocalite(): ?Localite
    {
        return $this->localite;
    }

    public function setLocalite(?Localite $localite): static
    {
        $this->localite = $localite;

        return $this;
    }

    /**
     * @return Collection<int, ProjetImage>
     */
    public function getProjetImages(): Collection
    {
        return $this->projetImages;
    }

    public function addProjetImage(ProjetImage $projetImage): static
    {
        if (!$this->projetImages->contains($projetImage)) {
            $this->projetImages->add($projetImage);
            $projetImage->setProjet($this);
        }

        return $this;
    }

    public function removeProjetImage(ProjetImage $projetImage): static
    {
        if ($this->projetImages->removeElement($projetImage)) {
            // set the owning side to null (unless already changed)
            if ($projetImage->getProjet() === $this) {
                $projetImage->setProjet(null);
            }
        }

        return $this;
    }
}

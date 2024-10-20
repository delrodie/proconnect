<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Repository\PrestataireRepository;
use App\State\PrestataireStateProcessor;
use App\State\PrestataireStateProvider;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ApiResource(
    operations: [
        new GetCollection(
            formats: ['json' => ['application/json'], 'ld+json' => ['application/ld+json']],
            normalizationContext: ['groups' => ['prestataire.list']],
            provider: PrestataireStateProvider::class
        ),
        new Post(
            inputFormats: ['multipart' => ['multipart/form-data']],
            outputFormats: ['json' => ['application/json']],
            denormalizationContext: ['groups' => ['prestataire.write']],
            processor: PrestataireStateProcessor::class
        ),
        new Get(
            formats: ['json' => ['application/json'], 'ld+json' => ['application/ld+json']],
            normalizationContext: ['groups' => ['prestataire.show']],
            provider: PrestataireStateProvider::class
        ),
        new Patch(
            formats: ['json' => ['application/json'], 'ld+json' => ['application/ld+json']],
            denormalizationContext: ['groups' => ['prestataire.update']],
            processor: PrestataireStateProcessor::class
        )
    ],
//    normalizationContext: ['groups' => ['prestataire.list', 'prestataire.show']],
//    denormalizationContext: ['groups' => ['prestataire.write']]
)]
#[ORM\Entity(repositoryClass: PrestataireRepository::class)]
class Prestataire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['message.show', 'prestataire.list'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['message.show', 'prestataire.list'])]
    private ?string $matricule = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['message.show', 'prestataire.list', 'prestataire.write', 'prestataire.update'])]
    private ?string $nom = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['message.show', 'prestataire.list', 'prestataire.write', 'prestataire.update'])]
    private ?string $prenoms = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['message.show', 'prestataire.list', 'prestataire.write', 'prestataire.update'])]
    private ?string $sexe = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['message.show', 'prestataire.list', 'prestataire.write', 'prestataire.update'])]
    private ?string $profession = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['message.show', 'prestataire.list', 'prestataire.write', 'prestataire.update'])]
    private ?string $adresse = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['groups' => 'prestataire.write', 'prestataire.update'])]
    private ?string $geolocalisation = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['message.show', 'prestataire.list', 'prestataire.write', 'prestataire.update'])]
    private ?string $niveau = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['prestataire.write', 'prestataire.update'])]
    private ?string $experience = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups('prestataire.write', 'prestataire.update')]
    private ?string $langue = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['prestataire.write', 'prestataire.update'])]
    private ?int $tarifHoraire = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['prestataire.write', 'prestataire.update'])]
    private ?string $stock = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['prestataire.write', 'prestataire.update'])]
    private ?string $paiement = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['prestataire.write', 'prestataire.update'])]
    private ?string $garantie = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['prestataire.write'])]
    private ?string $casier = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['prestataire.write', 'prestataire.update'])]
    private ?string $modeTravail = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['prestataire.list', 'prestataire.show', 'prestataire.write'])]
    private ?string $media = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['prestataire.write'])]
    private ?string $licence = null;

    /**
     * @var Collection<int, Competence>
     */
    #[ORM\ManyToMany(targetEntity: Competence::class)]
    #[Groups(['message.show', 'prestataire.show', 'prestataire.write', 'prestataire.update'])]
    private Collection $competence;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['prestataire.write', 'prestataire.update'])]
    private ?string $deplacement = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['message.show', 'prestataire.list', 'prestataire.write', 'prestataire.update'])]
    private ?string $email = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['message.show', 'prestataire.list', 'prestataire.write', 'prestataire.update'])]
    private ?string $telephone = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[Groups(['prestataire.write'])]
    private ?User $user = null;

    #[ORM\ManyToOne]
    #[Groups(['message.show', 'prestataire.list', 'prestataire.write', 'prestataire.update'])]
    private ?Localite $localite = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $slug = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['prestataire.write', 'prestataire.update'])]
    private ?string $biographie = null;

    /**
     * @var Collection<int, Message>
     */
    #[ORM\OneToMany(targetEntity: Message::class, mappedBy: 'prestataire')]
    private Collection $messages;

    public function __construct()
    {
        $this->competence = new ArrayCollection();
        $this->messages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMatricule(): ?string
    {
        return $this->matricule;
    }

    public function setMatricule(string $matricule): static
    {
        $this->matricule = $matricule;

        return $this;
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

    public function getPrenoms(): ?string
    {
        return $this->prenoms;
    }

    public function setPrenoms(?string $prenoms): static
    {
        $this->prenoms = $prenoms;

        return $this;
    }

    public function getSexe(): ?string
    {
        return $this->sexe;
    }

    public function setSexe(?string $sexe): static
    {
        $this->sexe = $sexe;

        return $this;
    }

    public function getProfession(): ?string
    {
        return $this->profession;
    }

    public function setProfession(?string $profession): static
    {
        $this->profession = $profession;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(?string $adresse): static
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getGeolocalisation(): ?string
    {
        return $this->geolocalisation;
    }

    public function setGeolocalisation(?string $geolocalisation): static
    {
        $this->geolocalisation = $geolocalisation;

        return $this;
    }

    public function getNiveau(): ?string
    {
        return $this->niveau;
    }

    public function setNiveau(?string $niveau): static
    {
        $this->niveau = $niveau;

        return $this;
    }

    public function getExperience(): ?string
    {
        return $this->experience;
    }

    public function setExperience(?string $experience): static
    {
        $this->experience = $experience;

        return $this;
    }

    public function getLangue(): ?string
    {
        return $this->langue;
    }

    public function setLangue(?string $langue): static
    {
        $this->langue = $langue;

        return $this;
    }

    public function getTarifHoraire(): ?int
    {
        return $this->tarifHoraire;
    }

    public function setTarifHoraire(?int $tarifHoraire): static
    {
        $this->tarifHoraire = $tarifHoraire;

        return $this;
    }

    public function getStock(): ?string
    {
        return $this->stock;
    }

    public function setStock(?string $stock): static
    {
        $this->stock = $stock;

        return $this;
    }

    public function getPaiement(): ?string
    {
        return $this->paiement;
    }

    public function setPaiement(?string $paiement): static
    {
        $this->paiement = $paiement;

        return $this;
    }

    public function getGarantie(): ?string
    {
        return $this->garantie;
    }

    public function setGarantie(?string $garantie): static
    {
        $this->garantie = $garantie;

        return $this;
    }

    public function getCasier(): ?string
    {
        return $this->casier;
    }

    public function setCasier(?string $casier): static
    {
        $this->casier = $casier;

        return $this;
    }

    public function getModeTravail(): ?string
    {
        return $this->modeTravail;
    }

    public function setModeTravail(?string $modeTravail): static
    {
        $this->modeTravail = $modeTravail;

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

    public function getLicence(): ?string
    {
        return $this->licence;
    }

    public function setLicence(?string $licence): static
    {
        $this->licence = $licence;

        return $this;
    }

    /**
     * @return Collection<int, Competence>
     */
    public function getCompetence(): Collection
    {
        return $this->competence;
    }

    public function addCompetence(Competence $competence): static
    {
        if (!$this->competence->contains($competence)) {
            $this->competence->add($competence);
        }

        return $this;
    }

    public function removeCompetence(Competence $competence): static
    {
        $this->competence->removeElement($competence);

        return $this;
    }

    public function getDeplacement(): ?string
    {
        return $this->deplacement;
    }

    public function setDeplacement(?string $deplacement): static
    {
        $this->deplacement = $deplacement;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): static
    {
        $this->telephone = $telephone;

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

    public function getLocalite(): ?Localite
    {
        return $this->localite;
    }

    public function setLocalite(?Localite $localite): static
    {
        $this->localite = $localite;

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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getBiographie(): ?string
    {
        return $this->biographie;
    }

    public function setBiographie(?string $biographie): static
    {
        $this->biographie = $biographie;

        return $this;
    }

    /**
     * @return Collection<int, Message>
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(Message $message): static
    {
        if (!$this->messages->contains($message)) {
            $this->messages->add($message);
            $message->setPrestataire($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): static
    {
        if ($this->messages->removeElement($message)) {
            // set the owning side to null (unless already changed)
            if ($message->getPrestataire() === $this) {
                $message->setPrestataire(null);
            }
        }

        return $this;
    }
}

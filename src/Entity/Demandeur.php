<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Repository\DemandeurRepository;
use App\State\DemandeurStateProcessor;
use App\State\DemandeurStateProvider;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ApiResource(
    operations: [
        new GetCollection(
            formats: ['json' => ['application/json'], 'ld+json' => ['application/ld+json']],
            normalizationContext: ['groups' => ['demandeur.list'],
        ],
            provider: DemandeurStateProvider::class),
            new Get(
                formats: ['json' => ['application/json'], 'ld+json' => ['application/ld+json']],
                normalizationContext: ['groups' => 'demandeur.show'],
                provider: DemandeurStateProvider::class,
            ),
        new Patch(
            formats: ['json' => ['application/json'], 'ld+json' => ['application/ld+json']],
//            inputFormats: ['multipart' => ['multipart/form-data']],
//            outputFormats: ['json' => ['application/json']],
            denormalizationContext: ['groups' => 'demandeur.write'],
            processor: DemandeurStateProcessor::class,
        ),
        new Post(
            inputFormats: ['multipart' => ['multipart/form-data']],
            outputFormats: ['json' => ['application/json']],
            denormalizationContext: ['groups' => 'demandeur.write'],
            processor: DemandeurStateProcessor::class
        ),
    ],
//    formats: ['json' => ['application/json'], 'ld+json' => ['application/ld+json']],
    paginationEnabled: false,
)]
#[ApiResource(
    uriTemplate: '/localites/{localiteId}/demandeurs/{id}',
    operations: [
        new Get()
    ],
    uriVariables: [
        'localiteId' => new Link(toProperty: 'localite', fromClass: Localite::class),
        'id' => new Link(fromClass: Demandeur::class)
    ]
)]
#[ApiResource(
    uriTemplate: '/localites/{localiteId}/demandeurs',
    operations: [new GetCollection()],
    uriVariables: [
        'localiteId' => new Link(toProperty: 'localite', fromClass: Localite::class)
    ],
    paginationEnabled: false,
)]
#[ORM\Entity(repositoryClass: DemandeurRepository::class)]
class Demandeur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['demandeur.list'])]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['demandeur.list'])]
    private ?string $code = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['demandeur.list', 'demandeur.write'])]
    private ?string $nom = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['demandeur.list', 'demandeur.write'])]
    private ?string $prenom = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['demandeur.list', 'demandeur.write'])]
    private ?string $email = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['demandeur.list', 'demandeur.write'])]
    private ?string $telephone = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['demandeur.list', 'demandeur.write'])]
    private ?string $profession = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['demandeur.list', 'demandeur.write'])]
    private ?string $adresse = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['demandeur.list', 'demandeur.write'])]
    private ?string $media = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['demandeur.list'])]
    private ?string $slug = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Groups(['demandeur.list', 'demandeur.show'])]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[Groups(['demandeur.write'])]
    private ?User $user = null;

    #[ORM\ManyToOne]
    #[Groups(['demandeur.list', 'demandeur.write'])]
    private ?Localite $localite = null;

    /**
     * @var Collection<int, Message>
     */
    #[ORM\OneToMany(targetEntity: Message::class, mappedBy: 'demandeur')]
    #[Groups(['message.show'])]
    private Collection $messages;

    public function __construct()
    {
        $this->messages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): static
    {
        $this->code = $code;

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

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(?string $prenom): static
    {
        $this->prenom = $prenom;

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

    public function getMedia(): ?string
    {
        return $this->media;
    }

    public function setMedia(?string $media): static
    {
        $this->media = $media;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?\Symfony\Component\String\AbstractUnicodeString $slug): static
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
            $message->setDemandeur($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): static
    {
        if ($this->messages->removeElement($message)) {
            // set the owning side to null (unless already changed)
            if ($message->getDemandeur() === $this) {
                $message->setDemandeur(null);
            }
        }

        return $this;
    }
}

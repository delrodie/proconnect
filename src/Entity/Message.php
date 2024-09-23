<?php

namespace App\Entity;

use App\Repository\MessageRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MessageRepository::class)]
class Message
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $content = null;

    #[ORM\ManyToOne(inversedBy: 'messages')]
    private ?Demandeur $demandeur = null;

    #[ORM\ManyToOne(inversedBy: 'messages')]
    private ?Prestataire $prestataire = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?bool $vue = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $vueAt = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $reference = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $emetteur = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getDemandeur(): ?Demandeur
    {
        return $this->demandeur;
    }

    public function setDemandeur(?Demandeur $demandeur): static
    {
        $this->demandeur = $demandeur;

        return $this;
    }

    public function getPrestataire(): ?Prestataire
    {
        return $this->prestataire;
    }

    public function setPrestataire(?Prestataire $prestataire): static
    {
        $this->prestataire = $prestataire;

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

    public function isVue(): ?bool
    {
        return $this->vue;
    }

    public function setVue(?bool $vue): static
    {
        $this->vue = $vue;

        return $this;
    }

    public function getVueAt(): ?\DateTimeInterface
    {
        return $this->vueAt;
    }

    public function setVueAt(?\DateTimeInterface $vueAt): static
    {
        $this->vueAt = $vueAt;

        return $this;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(?int $reference): static
    {
        $this->reference = $reference;

        return $this;
    }

    public function getEmetteur(): ?string
    {
        return $this->emetteur;
    }

    public function setEmetteur(?string $emetteur): static
    {
        $this->emetteur = $emetteur;

        return $this;
    }
}

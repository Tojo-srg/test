<?php

namespace App\Entity;

use App\Repository\FactureRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FactureRepository::class)
 */
class Facture
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\Column(type="float")
     */
    private ?float $montant;

    /**
     * @ORM\Column(type="datetime")
     */
    private ?\DateTimeInterface $envoye;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $status;

    /**
     * @ORM\ManyToOne(targetEntity=Client::class, inversedBy="factures")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Client $client;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMontant(): ?float
    {
        return $this->montant;
    }

    public function setMontant(float $montant): self
    {
        $this->montant = $montant;

        return $this;
    }

    public function getEnvoye(): ?\DateTimeInterface
    {
        return $this->envoye;
    }

    public function setEnvoye(\DateTimeInterface $envoye): self
    {
        $this->envoye = $envoye;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): self
    {
        $this->client = $client;

        return $this;
    }
}

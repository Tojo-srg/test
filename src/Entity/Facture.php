<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\FactureRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=FactureRepository::class)
 * @ApiResource(
 *     attributes={
            "pagination_enabled"=true,
 *          "pagination_items_per_page"=10,
 *          "order": {"envoye":"desc"}
 *     },
 *     normalizationContext={
            "groups"={"factures_read"}
 *     }
 * )
 * @ApiFilter(OrderFilter::class, properties={"montant","envoye"})
 */
class Facture
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"factures_read", "clients_read"})
     */
    private ?int $id;

    /**
     * @ORM\Column(type="float")
     * @Groups({"factures_read", "clients_read"})
     */
    private ?float $montant;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"factures_read", "clients_read"})
     */
    private ?\DateTimeInterface $envoye;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"factures_read", "clients_read"})
     */
    private ?string $status;

    /**
     * @ORM\ManyToOne(targetEntity=Client::class, inversedBy="factures")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"factures_read"})
     */
    private ?Client $client;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"factures_read", "clients_read"})
     */
    private ?int $chrono;

    /**
     * Récupérer le User à qui appartient la facture
     *
     * @Groups({"factures_read"})
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->client->getUser();
    }

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

    public function getChrono(): ?int
    {
        return $this->chrono;
    }

    public function setChrono(int $chrono): self
    {
        $this->chrono = $chrono;

        return $this;
    }
}

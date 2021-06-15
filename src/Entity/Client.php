<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ClientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\OrderFilter;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=ClientRepository::class)
 * @ApiResource(
 *     normalizationContext={
            "groups"={"clients_read"}
 *     }
 * )
 * @ApiFilter(SearchFilter::class)
 * @ApiFilter(OrderFilter::class)
 */
class Client
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"clients_read", "factures_read"})
     */
    private ?int $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"clients_read", "factures_read"})
     */
    private ?string $nom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"clients_read", "factures_read"})
     */
    private ?string $prenom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"clients_read", "factures_read"})
     */
    private ?string $email;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"clients_read", "factures_read"})
     */
    private ?string $entreprise;

    /**
     * @ORM\OneToMany(targetEntity=Facture::class, mappedBy="client")
     * @Groups({"clients_read"})
     */
    private Collection $factures;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="clients")
     * @Groups({"clients_read"})
     */
    private ?User $user;

    public function __construct()
    {
        $this->factures = new ArrayCollection();
    }

    /**
     * @Groups({"clients_read"})
     * @return float
     */
    public function getMontantTotal(): float
    {
        return array_reduce($this->factures->toArray(), function ($total, $facture) {
            return $total + $facture->getMontant();
        }, 0);
    }

    /**
     * Récupérer montant total non payé
     * @Groups({"clients_read"})
     * @return float
     */
    public function getMontantNonPaye(): float
    {
        return array_reduce($this->factures->toArray(), function ($total, $facture) {
            return $total + ($facture->getStatus() === "PAID" || $facture->getStatus() === "CANCELED" ? 0 : $facture->getMontant());
        });
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getEntreprise(): ?string
    {
        return $this->entreprise;
    }

    public function setEntreprise(?string $entreprise): self
    {
        $this->entreprise = $entreprise;

        return $this;
    }

    /**
     * @return Collection|Facture[]
     */
    public function getFactures(): Collection
    {
        return $this->factures;
    }

    public function addFacture(Facture $facture): self
    {
        if (!$this->factures->contains($facture)) {
            $this->factures[] = $facture;
            $facture->setClient($this);
        }

        return $this;
    }

    public function removeFacture(Facture $facture): self
    {
        if ($this->factures->removeElement($facture)) {
            // set the owning side to null (unless already changed)
            if ($facture->getClient() === $this) {
                $facture->setClient(null);
            }
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}

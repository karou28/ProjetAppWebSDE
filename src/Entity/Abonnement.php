<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AbonnementRepository")
 */
class Abonnement
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=200)
     */
    private $contrat;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\Column(type="float")
     */
    private $cumulAncien;

    /**
     * @ORM\Column(type="float")
     */
    private $cumulNouveau;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Compteur", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $compteur;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Facture", mappedBy="abonnement", orphanRemoval=true)
     */
    private $factures;

    public function __construct()
    {
        $this->factures = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContrat(): ?string
    {
        return $this->contrat;
    }

    public function setContrat(string $contrat): self
    {
        $this->contrat = $contrat;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getCumulAncien(): ?float
    {
        return $this->cumulAncien;
    }

    public function setCumulAncien(float $cumulAncien): self
    {
        $this->cumulAncien = $cumulAncien;

        return $this;
    }

    public function getCumulNouveau(): ?float
    {
        return $this->cumulNouveau;
    }

    public function setCumulNouveau(float $cumulNouveau): self
    {
        $this->cumulNouveau = $cumulNouveau;

        return $this;
    }

    public function getCompteur(): ?Compteur
    {
        return $this->compteur;
    }

    public function setCompteur(Compteur $compteur): self
    {
        $this->compteur = $compteur;

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
            $facture->setAbonnement($this);
        }

        return $this;
    }

    public function removeFacture(Facture $facture): self
    {
        if ($this->factures->contains($facture)) {
            $this->factures->removeElement($facture);
            // set the owning side to null (unless already changed)
            if ($facture->getAbonnement() === $this) {
                $facture->setAbonnement(null);
            }
        }

        return $this;
    }
}

<?php

namespace App\Entity;

use App\Repository\MenuAdulteRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MenuAdulteRepository::class)
 */
class MenuAdulte
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $titre;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $slug;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $statut;

    /**
     * @ORM\OneToOne(targetEntity=Adulte::class, mappedBy="menu", cascade={"persist", "remove"})
     */
    private $adulte;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(?string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getStatut(): ?bool
    {
        return $this->statut;
    }

    public function setStatut(?bool $statut): self
    {
        $this->statut = $statut;

        return $this;
    }

    public function getAdulte(): ?Adulte
    {
        return $this->adulte;
    }

    public function setAdulte(?Adulte $adulte): self
    {
        // unset the owning side of the relation if necessary
        if ($adulte === null && $this->adulte !== null) {
            $this->adulte->setMenu(null);
        }

        // set the owning side of the relation if necessary
        if ($adulte !== null && $adulte->getMenu() !== $this) {
            $adulte->setMenu($this);
        }

        $this->adulte = $adulte;

        return $this;
    }
}

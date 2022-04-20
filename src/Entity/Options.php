<?php

namespace App\Entity;

use App\Repository\OptionsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OptionsRepository::class)
 */
class Options
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $label;

    /**
     * @ORM\ManyToMany(targetEntity=vehicule::class, inversedBy="options")
     */
    private $vehicule;

    public function __construct()
    {
        $this->vehicule = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @return Collection|vehicule[]
     */
    public function getVehicule(): Collection
    {
        return $this->vehicule;
    }

    public function addVehicule(vehicule $vehicule): self
    {
        if (!$this->vehicule->contains($vehicule)) {
            $this->vehicule[] = $vehicule;
        }

        return $this;
    }

    public function removeVehicule(vehicule $vehicule): self
    {
        $this->vehicule->removeElement($vehicule);

        return $this;
    }
}

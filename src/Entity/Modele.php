<?php

namespace App\Entity;

use App\Repository\ModeleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ModeleRepository::class)
 */
class Modele
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
    public $nom;

    /**
     * @ORM\ManyToOne(targetEntity=Fabriquant::class, inversedBy="modeles")
     */
    public $fabricant;





    public function __construct()
    {
       
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

    /**
     * @return Collection<int, Fabriquant>
     */
    public function getFabriquants(): Collection
    {
        return $this->fabriquants;
    }




      
    public function __toString()
    {
        return $this->getNom();
    }

    public function getFabricant(): ?Fabriquant
    {
        return $this->fabricant;
    }

    public function setFabricant(?Fabriquant $fabricant): self
    {
        $this->fabricant = $fabricant;

        return $this;
    }



}

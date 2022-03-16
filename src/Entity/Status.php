<?php

namespace App\Entity;

use App\Repository\StatusRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=StatusRepository::class)
 */
class Status
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

  

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    private $nom;

    /**
     * @ORM\OneToMany(targetEntity=Leads::class, mappedBy="statusvehicule")
     */
    private $leads;

    public function __construct()
    {
        $this->leads = new ArrayCollection();
    }

 

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * @return Collection|Leads[]
     */
    public function getLeads(): Collection
    {
        return $this->leads;
    }

    public function addLead(Leads $lead): self
    {
        if (!$this->leads->contains($lead)) {
            $this->leads[] = $lead;
            $lead->setStatusvehicule($this);
        }

        return $this;
    }

    public function removeLead(Leads $lead): self
    {
        if ($this->leads->removeElement($lead)) {
            // set the owning side to null (unless already changed)
            if ($lead->getStatusvehicule() === $this) {
                $lead->setStatusvehicule(null);
            }
        }

        return $this;
    }
}


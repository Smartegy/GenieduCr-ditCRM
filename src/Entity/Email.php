<?php

namespace App\Entity;

use App\Repository\EmailRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EmailRepository::class)
 */
class Email
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
    private $objet;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $msg;

    /**
     * @ORM\ManyToMany(targetEntity=Leads::class, inversedBy="emails")
     */
    private $lead;

    public function __construct()
    {
        $this->lead = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getObjet(): ?string
    {
        return $this->objet;
    }

    public function setObjet(?string $objet): self
    {
        $this->objet = $objet;

        return $this;
    }

    public function getMsg(): ?string
    {
        return $this->msg;
    }

    public function setMsg(?string $msg): self
    {
        $this->msg = $msg;

        return $this;
    }

    /**
     * @return Collection|Leads[]
     */
    public function getLead(): Collection
    {
        return $this->lead;
    }

    public function addLead(Leads $lead): self
    {
        if (!$this->lead->contains($lead)) {
            $this->lead[] = $lead;
        }

        return $this;
    }

    public function removeLead(Leads $lead): self
    {
        $this->lead->removeElement($lead);

        return $this;
    }
}

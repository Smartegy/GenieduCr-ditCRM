<?php

namespace App\Entity;

use App\Repository\VendeurrRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=VendeurrRepository::class)
 */
class Vendeurr
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $actif;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $authenenvoiemail;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $authenenvoisms;

    /**
     * @ORM\Column(type="datetime")
     */
    private $datecreation;

    /**
     * @ORM\Column(type="datetime")
     */
    private $datemodification;

    /**
     * @ORM\OneToOne(targetEntity=Utilisateur::class, cascade={"persist","remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $utilisateur;

    /**
     * @ORM\OneToMany(targetEntity=Leads::class, mappedBy="vendeurr")
     */
    private $leads;

    /**
     * @ORM\ManyToMany(targetEntity=Concessionnairemarchand::class, mappedBy="vendeurrs")
     */
    private $concessionnairemarchands;

    /**
     * @ORM\ManyToMany(targetEntity=Partenaire::class, mappedBy="vendeurrs")
     */
    private $partenaire;



    public function __construct()
    {
        $this->leads = new ArrayCollection();
        $this->concessionnairemarchands = new ArrayCollection();
        $this->partenaire = new ArrayCollection();

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getActif(): ?bool
    {
        return $this->actif;
    }

    public function setActif(?bool $actif): self
    {
        $this->actif = $actif;

        return $this;
    }

    /**
     * @return Collection|Concessionnairemarchand[]
     */
    public function getConcessionnairemarchands(): Collection
    {
        return $this->concessionnairemarchands;
    }
    public function addConcessionnairemarchand(Concessionnairemarchand $concessionnairemarchand): self
    {
        if (!$this->concessionnairemarchands->contains($concessionnairemarchand)) {
            $this->concessionnairemarchands[] = $concessionnairemarchand;
            $concessionnairemarchand->addVendeur($this);
        }

        return $this;
    }

    public function removeConcessionnairemarchand(Concessionnairemarchand $concessionnairemarchand): self
    {
        if ($this->concessionnairemarchands->removeElement($concessionnairemarchand)) {
            $concessionnairemarchand->removeVendeur($this);
        }

        return $this;
    }

    /**
     * @return Collection|Partenaire[]
     */
    public function getPartenaire(): Collection
    {
        return $this->partenaire;
    }

    public function getAuthenenvoiemail(): ?bool
    {
        return $this->authenenvoiemail;
    }

    public function setAuthenenvoiemail(?bool $authenenvoiemail): self
    {
        $this->authenenvoiemail = $authenenvoiemail;

        return $this;
    }

    public function getAuthenenvoisms(): ?bool
    {
        return $this->authenenvoisms;
    }

    public function setAuthenenvoisms(?bool $authenenvoisms): self
    {
        $this->authenenvoisms = $authenenvoisms;

        return $this;
    }

    public function getDatecreation(): ?\DateTimeInterface
    {
        return $this->datecreation;
    }

    public function setDatecreation(\DateTimeInterface $datecreation): self
    {
        $this->datecreation = $datecreation;

        return $this;
    }

    public function getDatemodification(): ?\DateTimeInterface
    {
        return $this->datemodification;
    }

    public function setDatemodification(\DateTimeInterface $datemodification): self
    {
        $this->datemodification = $datemodification;

        return $this;
    }

    public function getUtilisateur(): ?Utilisateur
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(Utilisateur $utilisateur): self
    {
        $this->utilisateur = $utilisateur;

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
            $lead->setVendeurr($this);
        }

        return $this;
    }

    public function removeLead(Leads $lead): self
    {
        if ($this->leads->removeElement($lead)) {
            // set the owning side to null (unless already changed)
            if ($lead->getVendeurr() === $this) {
                $lead->setVendeurr(null);
            }
        }

        return $this;
    }


    public function setConcessionnairemarchands(string $concessionnairemarchands): self
    {
        $this->concessionnairemarchands = $concessionnairemarchands;

        return $this;
    }
}

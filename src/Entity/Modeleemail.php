<?php

namespace App\Entity;

use App\Repository\ModeleemailRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Form\FormTypeInterface;

/**
 * @ORM\Entity(repositoryClass=ModeleemailRepository::class)
 */
class Modeleemail
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
    private $titre;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $sujetemail;

    /**
     * @ORM\Column(type="text", length=255, length="4294967292")
     */
    private $message;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $user ='agent';

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $datecreationtable;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $datemodification;

    /**
     * @ORM\OneToMany(targetEntity=Courriel::class, mappedBy="modele")
     */
    private $courriels;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $sms;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $mail;

    public function __construct()
    {
       

            if($this->datecreationtable == null)
            {
            $this->datecreationtable = new DateTime('now');
            }
        
            $this->datemodification = new DateTime('now');
            $this->courriels = new ArrayCollection();
   
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getSujetemail(): ?string
    {
        return $this->sujetemail;
    }

    public function setSujetemail(string $sujetemail): self
    {
        $this->sujetemail = $sujetemail;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getUser(): ?string
    {
        return $this->user;
    }

    public function setUser(?string $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getDatecreationtable(): ?\DateTimeInterface
    {
        return $this->datecreationtable;
    }

    public function setDatecreationtable(\DateTimeInterface $datecreationtable): self
    {
        $this->datecreationtable = $datecreationtable;

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

    /**
     * @return Collection|Courriel[]
     */
    public function getCourriels(): Collection
    {
        return $this->courriels;
    }

    public function addCourriel(Courriel $courriel): self
    {
        if (!$this->courriels->contains($courriel)) {
            $this->courriels[] = $courriel;
            $courriel->setModele($this);
        }

        return $this;
    }

    public function removeCourriel(Courriel $courriel): self
    {
        if ($this->courriels->removeElement($courriel)) {
            // set the owning side to null (unless already changed)
            if ($courriel->getModele() === $this) {
                $courriel->setModele(null);
            }
        }

        return $this;
    }

    public function getSms(): ?bool
    {
        return $this->sms;
    }

    public function setSms(?bool $sms): self
    {
        $this->sms = $sms;

        return $this;
    }

    public function getMail(): ?bool
    {
        return $this->mail;
    }

    public function setMail(?bool $mail): self
    {
        $this->mail = $mail;

        return $this;
    }
}

<?php

namespace App\Entity;

use App\Repository\SmsRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SmsRepository::class)
 */
class Sms
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
    public $recepteur;
        /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    public $emetteur;



    /**
     * @ORM\ManyToOne(targetEntity=Leads::class, inversedBy="sms")
 
    * @ORM\JoinColumn(referencedColumnName="id", nullable=true,onDelete="SET NULL")
     */
    public $lead;

    /**
     * @ORM\ManyToOne(targetEntity=Modelesms::class, inversedBy="sms")
     */
    public $modele;

    /**
     * @ORM\Column(type="datetime")
     */
    public $datecreation;

    /**
     * @ORM\Column(type="datetime")
     */
    public $datemodification;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $text;

    public function __construct()
    {
       

        if($this->datecreation == null){
            $this->datecreation = new DateTime('now');
        }
        
        $this->datemodification = new DateTime('now');
        
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRecepteur(): ?string
    {
        return $this->recepteur;
    }

    public function setRecepteur(string $recepteur): self
    {
        $this->recepteur = $recepteur;

        return $this;
    }
    public function getEmetteur(): ?string
    {
        return $this->emetteur;
    }

    public function setEmetteur(string $emetteur): self
    {
        $this->emetteur = $emetteur;

        return $this;
    }
  

    public function getLead(): ?Leads
    {
        return $this->lead;
    }

    public function setLead(?Leads $lead): self
    {
        $this->lead = $lead;

        return $this;
    }

    public function getModele(): ?Modelesms
    {
        return $this->modele;
    }

    public function setModele(?Modelesms $modele): self
    {
        $this->modele = $modele;

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

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }
}

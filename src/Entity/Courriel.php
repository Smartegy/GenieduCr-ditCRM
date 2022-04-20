<?php

namespace App\Entity;

use App\Repository\CourrielRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CourrielRepository::class)
 */
class Courriel
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
    private $recepteur;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $sujet;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $text;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $emetteur;

    /**
     * @ORM\ManyToOne(targetEntity=Leads::class, inversedBy="courriels")
     */
    private $lead;

    /**
     * @ORM\ManyToOne(targetEntity=Modeleemail::class, inversedBy="courriels")
     */
    public $modele;
      /**
     * @ORM\Column(type="datetime")
     */
    private $datecreation;

    /**
     * @ORM\Column(type="datetime")
     */
    private $datemodification;


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

  

    public function getSujet(): ?string
    {
        return $this->sujet;
    }

    public function setSujet(?string $sujet): self
    {
        $this->sujet = $sujet;

        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(?string $text): self
    {
        $this->text = $text;

        return $this;
    }

   

    public function getLead(): ?leads
    {
        return $this->lead;
    }

    public function setLead(?leads $lead): self
    {
        $this->lead = $lead;

        return $this;
    }

    public function getModele(): ?Modeleemail
    {
        return $this->modele;
    }

    public function setModele(?Modeleemail $modele): self
    {
        $this->modele = $modele;

        return $this;
    }

    /**
     * Get the value of recepteur
     */ 
    public function getRecepteur()
    {
        return $this->recepteur;
    }

    /**
     * Set the value of recepteur
     *
     * @return  self
     */ 
    public function setRecepteur($recepteur)
    {
        $this->recepteur = $recepteur;

        return $this;
    }

    /**
     * Get the value of emetteur
     */ 
    public function getEmetteur()
    {
        return $this->emetteur;
    }

    /**
     * Set the value of emetteur
     *
     * @return  self
     */ 
    public function setEmetteur($emetteur)
    {
        $this->emetteur = $emetteur;

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


    
}

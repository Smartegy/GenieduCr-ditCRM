<?php

namespace App\Entity;

use App\Repository\OperationAchatRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OperationAchatRepository::class)
 */
class OperationAchat 
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * 
     * 
     */
    public $numserie;

    /**
     * @ORM\OneToOne(targetEntity=Vehicule::class, cascade={"persist", "remove"})
     */
    public $vehicule;

    /**
     * @ORM\ManyToOne(targetEntity=Leads::class, inversedBy="operationAchats")
     * @ORM\JoinColumn(referencedColumnName="id",nullable=true,onDelete="SET NULL")
     */


     

    public $leads;

    /**
     * @ORM\Column(type="datetime")
     */
    private $datecreation;

    /**
     * @ORM\Column(type="datetime")
     */
    private $datemodification;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    public $modele;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    public $marque;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    public $prix_achat;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    public $prix_vente;




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

    public function getNumserie(): ?string
    {
        return $this->numserie;
    }

    public function setNumserie(string $numserie): self
    {
        $this->numserie = $numserie;

        return $this;
    }

    public function getVehicule(): ?Vehicule
    {
        return $this->vehicule;
    }

    public function setVehicule(?Vehicule $vehicule): self
    {
        $this->vehicule = $vehicule;

        return $this;
    }

    public function getLeads(): ?Leads
    {
        return $this->leads;
    }

    public function setLeads(?Leads $leads): self
    {
        $this->leads = $leads;

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
 

    public function getModele(): ?string
    {
        return $this->modele;
    }

    public function setModele(?string $modele): self       
    {
        $this->modele = $modele;

        return $this;
    }

    public function getMarque(): ?string
    {
        return $this->marque;
    }

    public function setMarque(?string $marque): self
    {
        $this->marque = $marque;

        return $this;
    }

    public function getPrixAchat(): ?float
    {
        return $this->prix_achat;
    }

    public function setPrixAchat(?float $prix_achat): self
    {
        $this->prix_achat = $prix_achat;

        return $this;
    }

    public function getPrixVente(): ?float
    {
        return $this->prix_vente;
    }

    public function setPrixVente(?float $prix_vente): self
    {
        $this->prix_vente = $prix_vente;

        return $this;
    }
    
    public function __toString()
    {
        return $this->numserie;
    }

}

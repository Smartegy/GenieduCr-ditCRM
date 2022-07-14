<?php

namespace App\Entity;

use App\Repository\FilesLeadRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @ORM\Entity(repositoryClass=FilesLeadRepository::class)
 */
class FilesLead
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
 
     * 
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $lien;

    /**
     * @ORM\ManyToOne(targetEntity=Leads::class, inversedBy="filesLeads")
     */
    private $lead;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $titre;

    /**
     * @ORM\Column(type="datetime")
     */
    public $datecreation;

    /**
     * @ORM\Column(type="datetime")
     */
    public $datemodification;


    public function __construct()
    {
       

        if($this->datecreation == null){
            $this->datecreation = new DateTime('now');
        }
        
        $this->datemodification = new DateTime('now');
        
    }




    public function setLeadFile(UploadedFile $galerie):void
    {
        $this->leadFile= $galerie;
        

    }

    public function getLeadFile()
    {
        return $this->leadFile;
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

    public function getLien(): ?string
    {
        return $this->lien;
    }

    public function setLien(?string $lien): self
    {
        $this->lien = $lien;

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

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(?string $titre): self
    {
        $this->titre = $titre;

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

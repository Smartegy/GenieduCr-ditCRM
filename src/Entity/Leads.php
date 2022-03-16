<?php

namespace App\Entity;

use App\Repository\LeadsRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LeadsRepository::class)
 */
class Leads
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
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $telephone;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $courriel;

    /**
     * @ORM\Column(type="datetime")
     */
    private $datecreation;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $commantaire;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $numserie;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $budgetmonsuelle;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $datenaissance;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $statutprofessionnel;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $revenumensuel;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $depuisquand;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nomcompagnie;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $occupationposte;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $adressedomicile;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $locationproprietaire;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $paiementmonsuel;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $date;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $datecreationtable;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $datemodificationtable;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $rappel;

    /**
     * @ORM\ManyToMany(targetEntity=Modeleemail::class)
     */
    private $emailleads;

    /**
     * @ORM\ManyToMany(targetEntity=Modelesms::class)
     */
    private $smsleads;

    /**
     * @ORM\ManyToOne(targetEntity=Statusleads::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $statusleads;

    /**
     * @ORM\ManyToOne(targetEntity=SourcesLeads::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $sourcesleads;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $marque;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $modele;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $annee;

    /**
     * @ORM\OneToMany(targetEntity=Operations::class, mappedBy="lead", orphanRemoval=true)
     */
    private $operations;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $type;




    /**
     * @ORM\ManyToOne(targetEntity=Administrateur::class, inversedBy="leads")
     * @ORM\JoinColumn(nullable=false)
     */
    private $administrateur;

    /**
     * @ORM\ManyToOne(targetEntity=Agent::class, inversedBy="leads")
     * @ORM\JoinColumn(nullable=false)
     */
    private $agent;

    /**
     * @ORM\ManyToOne(targetEntity=Partenaire::class, inversedBy="leads")
     * @ORM\JoinColumn(nullable=false)
     */
    private $partenaire;

    /**
     * @ORM\ManyToOne(targetEntity=Concessionnaire::class, inversedBy="leads")
      * @ORM\JoinColumn(nullable=false)
     */
    private $concessionnaire;

    /**
     * @ORM\ManyToOne(targetEntity=Marchand::class, inversedBy="leads")
     * @ORM\JoinColumn(nullable=false)
     */
    private $marchand;

    /**
     * @ORM\ManyToOne(targetEntity=Status::class, inversedBy="leads")
     * @ORM\JoinColumn(nullable=false)
     */
    private $statusvehicule;
    



    public function __construct()
    {
        $this->emailleads = new ArrayCollection();
        $this->smsleads = new ArrayCollection();
        $this->operations = new ArrayCollection();

            if($this->datecreationtable == null)
            {
            $this->datecreationtable = new DateTime('now');
            }
        
            $this->datemodificationtable = new DateTime('now');
         
        
   
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

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getCourriel(): ?string
    {
        return $this->courriel;
    }

    public function setCourriel(string $courriel): self
    {
        $this->courriel = $courriel;

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

    public function getCommantaire(): ?string
    {
        return $this->commantaire;
    }

    public function setCommantaire(?string $commantaire): self
    {
        $this->commantaire = $commantaire;

        return $this;
    }

    public function getNumserie(): ?string
    {
        return $this->numserie;
    }

    public function setNumserie(?string $numserie): self
    {
        $this->numserie = $numserie;

        return $this;
    }

    public function getBudgetmonsuelle(): ?float
    {
        return $this->budgetmonsuelle;
    }

    public function setBudgetmonsuelle(?float $budgetmonsuelle): self
    {
        $this->budgetmonsuelle = $budgetmonsuelle;

        return $this;
    }

    public function getDatenaissance(): ?\DateTimeInterface
    {
        return $this->datenaissance;
    }

    public function setDatenaissance(?\DateTimeInterface $datenaissance): self
    {
        $this->datenaissance = $datenaissance;

        return $this;
    }

    public function getStatutprofessionnel(): ?string
    {
        return $this->statutprofessionnel;
    }

    public function setStatutprofessionnel(?string $statutprofessionnel): self
    {
        $this->statutprofessionnel = $statutprofessionnel;

        return $this;
    }

    public function getRevenumensuel(): ?float
    {
        return $this->revenumensuel;
    }

    public function setRevenumensuel(?float $revenumensuel): self
    {
        $this->revenumensuel = $revenumensuel;

        return $this;
    }

    public function getDepuisquand(): ?\DateTimeInterface
    {
        return $this->depuisquand;
    }

    public function setDepuisquand(?\DateTimeInterface $depuisquand): self
    {
        $this->depuisquand = $depuisquand;

        return $this;
    }

    public function getNomcompagnie(): ?string
    {
        return $this->nomcompagnie;
    }

    public function setNomcompagnie(?string $nomcompagnie): self
    {
        $this->nomcompagnie = $nomcompagnie;

        return $this;
    }

    public function getOccupationposte(): ?string
    {
        return $this->occupationposte;
    }

    public function setOccupationposte(?string $occupationposte): self
    {
        $this->occupationposte = $occupationposte;

        return $this;
    }

    public function getAdressedomicile(): ?string
    {
        return $this->adressedomicile;
    }

    public function setAdressedomicile(?string $adressedomicile): self
    {
        $this->adressedomicile = $adressedomicile;

        return $this;
    }

    public function getLocationproprietaire(): ?string
    {
        return $this->locationproprietaire;
    }

    public function setLocationproprietaire(?string $locationproprietaire): self
    {
        $this->locationproprietaire = $locationproprietaire;

        return $this;
    }

    public function getPaiementmonsuel(): ?float
    {
        return $this->paiementmonsuel;
    }

    public function setPaiementmonsuel(?float $paiementmonsuel): self
    {
        $this->paiementmonsuel = $paiementmonsuel;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getDatecreationtable(): ?\DateTimeInterface
    {
        return $this->datecreationtable;
    }

    public function setDatecreationtable(?\DateTimeInterface $datecreationtable): self
    {
        $this->datecreationtable = $datecreationtable;

        return $this;
    }

    public function getDatemodificationtable(): ?\DateTimeInterface
    {
        return $this->datemodificationtable;
    }

    public function setDatemodificationtable(?\DateTimeInterface $datemodificationtable): self
    {
        $this->datemodificationtable = $datemodificationtable;

        return $this;
    }

    public function getRappel(): ?\DateTimeInterface
    {
        return $this->rappel;
    }

    public function setRappel(?\DateTimeInterface $rappel): self
    {
        $this->rappel = $rappel;

        return $this;
    }

    /**
     * @return Collection|Modeleemail[]
     */
    public function getEmailleads(): Collection
    {
        return $this->emailleads;
    }

    public function addEmaillead(Modeleemail $emaillead): self
    {
        if (!$this->emailleads->contains($emaillead)) {
            $this->emailleads[] = $emaillead;
        }

        return $this;
    }

    public function removeEmaillead(Modeleemail $emaillead): self
    {
        $this->emailleads->removeElement($emaillead);

        return $this;
    }

    /**
     * @return Collection|Modelesms[]
     */
    public function getSmsleads(): Collection
    {
        return $this->smsleads;
    }

    public function addSmslead(Modelesms $smslead): self
    {
        if (!$this->smsleads->contains($smslead)) {
            $this->smsleads[] = $smslead;
        }

        return $this;
    }

    public function removeSmslead(Modelesms $smslead): self
    {
        $this->smsleads->removeElement($smslead);

        return $this;
    }

    public function getStatusleads(): ?Statusleads
    {
        return $this->statusleads;
    }

    public function setStatusleads(?Statusleads $statusleads): self
    {
        $this->statusleads = $statusleads;

        return $this;
    }

    public function getSourcesleads(): ?SourcesLeads
    {
        return $this->sourcesleads;
    }

    public function setSourcesleads(?SourcesLeads $sourcesleads): self
    {
        $this->sourcesleads = $sourcesleads;

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

    public function getModele(): ?string
    {
        return $this->modele;
    }

    public function setModele(?string $modele): self
    {
        $this->modele = $modele;

        return $this;
    }

    public function getAnnee(): ?string
    {
        return $this->annee;
    }

    public function setAnnee(?string $annee): self
    {
        $this->annee = $annee;

        return $this;
    }

    /**
     * @return Collection|Operations[]
     */
    public function getOperations(): Collection
    {
        return $this->operations;
    }

    public function addOperation(Operations $operation): self
    {
        if (!$this->operations->contains($operation)) {
            $this->operations[] = $operation;
            $operation->setLead($this);
        }

        return $this;
    }

    public function removeOperation(Operations $operation): self
    {
        if ($this->operations->removeElement($operation)) {
            // set the owning side to null (unless already changed)
            if ($operation->getLead() === $this) {
                $operation->setLead(null);
            }
        }

        return $this;
    }

    public function getType(): ?bool
    {
        return $this->type;
    }

    public function setType(?bool $type): self
    {
        $this->type = $type;

        return $this;
    }

   

    
    public function getAdministrateur(): ?Administrateur
    {
        return $this->administrateur;
    }

    public function setAdministrateur(?Administrateur $administrateur): self
    {
        $this->administrateur = $administrateur;

        return $this;
    }

    public function getAgent(): ?Agent
    {
        return $this->agent;
    }

    public function setAgent(?Agent $agent): self
    {

        $this->agent = $agent;

        return $this;
    }

    public function getPartenaire(): ?Partenaire
    {
        return $this->partenaire;
    }

    public function setPartenaire(?Partenaire $partenaire): self
    {
        $this->partenaire = $partenaire;

        return $this;
    }

    public function getConcessionnaire(): ?Concessionnaire
    {
        return $this->concessionnaire;
    }

    public function setConcessionnaire(?Concessionnaire $concessionnaire): self
    {
        $this->concessionnaire = $concessionnaire;

        return $this;
    }

    public function getMarchand(): ?Marchand
    {
        return $this->marchand;
    }

    public function setMarchand(?Marchand $marchand): self
    {
        $this->marchand = $marchand;

        return $this;
    }

    public function getStatusvehicule(): ?Status
    {
        return $this->statusvehicule;
    }

    public function setStatusvehicule(?Status $statusvehicule): self
    {
        $this->statusvehicule = $statusvehicule;

        return $this;
    }

 

}

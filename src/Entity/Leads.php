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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $telephone;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
   
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
     * @ORM\Column(type="integer", nullable=true)
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
     * @ORM\ManyToOne(targetEntity=Statusleads::class)
     * @ORM\JoinColumn(nullable=false)
     */
    
    public $statusleads;

    /**
     * @ORM\ManyToOne(targetEntity=SourcesLeads::class,inversedBy="leads")
    * @ORM\JoinColumn(referencedColumnName="id", nullable=true,onDelete="SET NULL")
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
     * @ORM\Column(type="boolean", nullable=true)
     */
    public $type;




    /**
     * @ORM\ManyToOne(targetEntity=Administrateur::class, inversedBy="leads")
     * @ORM\JoinColumn(referencedColumnName="id",nullable=true,onDelete="SET NULL")
     */
    private $administrateur;

    /**
     * @ORM\ManyToOne(targetEntity=Agent::class, inversedBy="leads")
     * @ORM\JoinColumn(referencedColumnName="id",nullable=true,onDelete="SET NULL")

 
     */
    private $agent;

    /**
     * @ORM\ManyToOne(targetEntity=Partenaire::class, inversedBy="leads")
     * @ORM\JoinColumn(referencedColumnName="id" ,nullable=true ,onDelete="SET NULL")
     
     */
    private $partenaire;

    /**
     * @ORM\ManyToOne(targetEntity=Concessionnaire::class, inversedBy="leads")
      * @ORM\JoinColumn(referencedColumnName="id" ,nullable=true,onDelete="SET NULL")
     */
    private $concessionnaire;

    /**
     * @ORM\ManyToOne(targetEntity=Marchand::class, inversedBy="leads")
     * @ORM\JoinColumn(referencedColumnName="id", nullable=true,onDelete="SET NULL")
     */
    private $marchand;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $statusvehicule;

    /**
     * @ORM\ManyToOne(targetEntity=Vendeurr::class, inversedBy="leads")
     * * @ORM\JoinColumn(referencedColumnName="id", nullable=true,onDelete="SET NULL")
     */
    private $vendeurr;

    
    /**
      * @ORM\OneToMany(targetEntity=Notes::class,mappedBy="lead",cascade={"persist", "remove"})

     */
    public $note;

    /**
     * @ORM\OneToMany(targetEntity=FilesLead::class,mappedBy="lead",cascade={"persist", "remove"})
   
     */
    public $filesLeads;

    /**
     * @ORM\ManyToMany(targetEntity=Email::class, mappedBy="lead", cascade={"persist", "remove"})
     */
    public $emails;

    /**
     * @ORM\OneToMany(targetEntity=Courriel::class,mappedBy="lead",cascade={"persist", "remove"})
     
     */
    
    public $courriels;

    /**
     * @ORM\OneToMany(targetEntity=Sms::class, mappedBy="lead")
     */
    public $sms;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    public $sujet;

    /**
     * @ORM\Column(type="text", length=255, length="4294967292", nullable=true)
     */
    public $text;
    /**
     * @ORM\Column(type="text", length=255, length="4294967292", nullable=true)
     */
    public $textsms;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    public $modele_vente;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    public $anne_vente;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    public $kilometrage_vente;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    public $etatcarossrie_vente;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    public $remplacer_vehicule;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    public $etatpneus_vente;



    /**
     * @ORM\OneToMany(targetEntity=OperationAchat::class, mappedBy="leads")
     */
    private $operationAchats;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    public $isCLient;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $prix_achat;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $prix_vente;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $marquevente;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    public $annee_revenu;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    public $mois_revenu;

    /**
     * @ORM\Column(type="string" , nullable=true)
     */
    public $annee_habitation;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    public $mois_habitation;




    


  
    



    public function __construct()
  
    {
       

        if($this->datecreationtable == null){
           $this->datecreation = new DateTime('now');
     
            $this->datecreationtable = new DateTime('now');
        }
        
        $this->datemodificationtable = new DateTime('now');
        $this->note = new ArrayCollection();
        $this->filesLeads = new ArrayCollection();
        $this->emails = new ArrayCollection();
        $this->courriels = new ArrayCollection();
        $this->sms = new ArrayCollection();

       $this->operationAchats = new ArrayCollection();
 

  
      
  
    
    }

    public function getId(): ?int
    {
        return $this->id ;
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

    public function getDatenaissance(): ?int
    {
        return $this->datenaissance;
    }

    public function setDatenaissance(?int $datenaissance): self
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

    public function setAgent(?Agent $agent = null): self
    {

        $this->agent = $agent;

        return $this;
    }

    public function getPartenaire(): ?Partenaire
    {
        return $this->partenaire;
    }

    public function setPartenaire(?Partenaire $partenaire =null): self
    {
        $this->partenaire = $partenaire;

        return $this;
    }

    public function getConcessionnaire(): ?Concessionnaire
    {
        return $this->concessionnaire;
    }

    public function setConcessionnaire(?Concessionnaire $concessionnaire = null): self
    {
        $this->concessionnaire = $concessionnaire;

        return $this;
    }

    public function getMarchand(): ?Marchand
    {
        return $this->marchand;
    }

    public function setMarchand(?Marchand $marchand = null): self
    {
        $this->marchand = $marchand;

        return $this;
    }

    public function getStatusvehicule(): ?string
    {
        return $this->statusvehicule;
    }

    public function setStatusvehicule(string $statusvehicule): self
    {
        $this->statusvehicule = $statusvehicule;

        return $this;
    }

    public function getVendeurr(): ?Vendeurr
    {
        return $this->vendeurr;
    }

    public function setVendeurr(?Vendeurr $vendeurr = null): self
    {
        $this->vendeurr = $vendeurr;

        return $this;
    }

    /**
     * @return Collection|Notes[]
     */
    public function getNote(): Collection
    {
        return $this->note;
    }

    public function addNote(Notes $note): self
    {
        if (!$this->note->contains($note)) {
            $this->note[] = $note;
            $note->setLead($this);
        }

        return $this;
    }

    public function removeNote(Notes $note): self
    {
        if ($this->note->removeElement($note)) {
            // set the owning side to null (unless already changed)
            if ($note->getLead() === $this) {
                $note->setLead(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|FilesLead[]
     */
    public function getFilesLeads(): Collection
    {
        return $this->filesLeads;
    }

    public function addFilesLead(FilesLead $filesLead): self
    {
        if (!$this->filesLeads->contains($filesLead)) {
            $this->filesLeads[] = $filesLead;
            $filesLead->setLead($this);
        }

        return $this;
    }

    public function removeFilesLead(FilesLead $filesLead): self
    {
        if ($this->filesLeads->removeElement($filesLead)) {
            // set the owning side to null (unless already changed)
            if ($filesLead->getLead() === $this) {
                $filesLead->setLead(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Email[]
     */
    public function getEmails(): Collection
    {
        return $this->emails;
    }

    public function addEmail(Email $email): self
    {
        if (!$this->emails->contains($email)) {
            $this->emails[] = $email;
            $email->addLead($this);
        }

        return $this;
    }

    public function removeEmail(Email $email): self
    {
        if ($this->emails->removeElement($email)) {
            $email->removeLead($this);
        }

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
            $courriel->setLead($this);
        }

        return $this;
    }

    public function removeCourriel(Courriel $courriel): self
    {
        if ($this->courriels->removeElement($courriel)) {
            // set the owning side to null (unless already changed)
            if ($courriel->getLead() === $this) {
                $courriel->setLead(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Sms[]
     */
    public function getSms(): Collection
    {
        return $this->sms;
    }

    public function addSms(Sms $sms): self
    {
        if (!$this->sms->contains($sms)) {
            $this->sms[] = $sms;
            $sms->setLead($this);
        }

        return $this;
    }

    public function removeSms(Sms $sms): self
    {
        if ($this->sms->removeElement($sms)) {
            // set the owning side to null (unless already changed)
            if ($sms->getLead() === $this) {
                $sms->setLead(null);
            }
        }

        return $this;
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

    public function getTextsms(): ?string
    {
        return $this->textsms;
    }

    public function setTextsms(?string $textsms): self
    {
        $this->textsms = $textsms;

        return $this;
    }

    public function getModeleVente(): ?string
    {
        return $this->modele_vente;
    }

    public function setModeleVente(?string $modele_vente): self
    {
        $this->modele_vente = $modele_vente;

        return $this;
    }

    public function getAnneVente(): ?int
    {
        return $this->anne_vente;
    }

    public function setAnneVente( ?int $anne_vente): self
    {
        $this->anne_vente = $anne_vente;

        return $this;
    }

 
   

    public function getKilometrageVente(): ?string
    {
        return $this->kilometrage_vente;
    }

    public function setKilometrageVente(?string $kilometrage_vente): self
    {
        $this->kilometrage_vente = $kilometrage_vente;

        return $this;
    }

    public function getEtatcarossrieVente(): ?string
    {
        return $this->etatcarossrie_vente;
    }

    public function setEtatcarossrieVente(?string $etatcarossrie_vente): self
    {
        $this->etatcarossrie_vente = $etatcarossrie_vente;

        return $this;
    }

    public function getRemplacerVehicule(): ?bool
    {
        return $this->remplacer_vehicule;
    }

    public function setRemplacerVehicule(?bool $remplacer_vehicule): self
    {
        $this->remplacer_vehicule = $remplacer_vehicule;

        return $this;
    }

    public function getEtatpneusVente(): ?string
    {
        return $this->etatpneus_vente;
    }

    public function setEtatpneusVente(?string $etatpneus_vente): self
    {
        $this->etatpneus_vente = $etatpneus_vente;

        return $this;
    }



    /**
     * @return Collection<int, OperationAchat>
     */
    public function getOperationAchats(): Collection
    {
        return $this->operationAchats;
    }

    public function addOperationAchat(OperationAchat $operationAchat): self
    {
        if (!$this->operationAchats->contains($operationAchat)) {
            $this->operationAchats[] = $operationAchat;
            $operationAchat->setLeads($this);
        }

        return $this;
    }

    public function removeOperationAchat(OperationAchat $operationAchat): self
    {
        if ($this->operationAchats->removeElement($operationAchat)) {
            // set the owning side to null (unless already changed)
            if ($operationAchat->getLeads() === $this) {
                $operationAchat->setLeads(null);
            }
        }

        return $this;
    }

    public function getIsCLient(): ?bool
    {
        return $this->isCLient;
    }

    public function setIsCLient(?bool $isCLient): self
    {
        $this->isCLient = $isCLient;

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

    public function getMarquevente(): ?string
    {
        return $this->marquevente;
    }

    public function setMarquevente(?string $marquevente): self
    {
        $this->marquevente = $marquevente;

        return $this;
    }

    public function getAnneeRevenu(): ?string
    {
        return $this->annee_revenu;
    }

    public function setAnneeRevenu(?string $annee_revenu): self
    {
        $this->annee_revenu = $annee_revenu;

        return $this;
    }

    public function getMoisRevenu(): ?string
    {
        return $this->mois_revenu;
    }

    public function setMoisRevenu(?string $mois_revenu): self
    {
        $this->mois_revenu = $mois_revenu;

        return $this;
    }

    public function getAnneeHabitation(): ?string
    {
        return $this->annee_habitation;
    }

    public function setAnneeHabitation(string $annee_habitation): self
    {
        $this->annee_habitation = $annee_habitation;

        return $this;
    }

    public function getMoisHabitation(): ?string
    {
        return $this->mois_habitation;
    }

    public function setMoisHabitation(?string $mois_habitation): self
    {
        $this->mois_habitation = $mois_habitation;

        return $this;
    }

   





   

   
 

}

<?php

namespace App\Entity;

use App\Repository\OperationsRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OperationsRepository::class)
 */
class Operations
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
    private $numserie;





    /**
     * @ORM\Column(type="datetime")
     */
    private $datecreationtable;

    /**
     * @ORM\Column(type="datetime")
     */

    private $datetimemodificationtable;







    public function __construct()
    {


        if($this->datecreationtable == null){
            $this->datecreationtable = new DateTime('now');
        }
        
        $this->datetimemodificationtable = new DateTime('now');
  
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





    public function getDatecreationtable(): ?\DateTimeInterface
    {
        return $this->datecreationtable;
    }

    public function setDatecreationtable(\DateTimeInterface $datecreationtable): self
    {
        $this->datecreationtable = $datecreationtable;

        return $this;
    }

    public function getDatetimemodificationtable(): ?\DateTimeInterface
    {
        return $this->datetimemodificationtable;
    }

    public function setDatetimemodificationtable(\DateTimeInterface $datetimemodificationtable): self
    {
        $this->datetimemodificationtable = $datetimemodificationtable;

        return $this;
    }



}

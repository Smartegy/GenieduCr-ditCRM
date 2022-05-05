<?php

namespace App\Repository;

use App\Entity\Courriel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Courriel|null find($id, $lockMode = null, $lockVersion = null)
 * @method Courriel|null findOneBy(array $criteria, array $orderBy = null)
 * @method Courriel[]    findAll()
 * @method Courriel[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CourrielRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Courriel::class);
    }

    // /**
    //  * @return Courriel[] Returns an array of Courriel objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Courriel
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
  


    public function findBycourriel($value)
    {
        return $this->createQueryBuilder('courriel')
            ->andWhere('courriel.emetteur = :val')
            ->andWhere('courriel.recepteur = :value')
            //->setParameters(['val'=> $value,'value'=> $valuee])
            ->setParameter('val', $value)
            ->setParameter('value', $value)
            //->orderBy('courriel.datecreation', 'DESC')
            ->getQuery()
            ->getResult()
    
        ;
    }



   public function findByEmetteur($value)
    {
        return $this->createQueryBuilder('courriel')
            ->andWhere('courriel.recepteur = :value')
            ->setParameter('value', $value)
         
            ->getQuery()
            ->getResult()
    
        ;
    }
    public function findByRecepteur($value)
    {
        return $this->createQueryBuilder('courriel')
            ->andWhere('courriel.emetteur = :val')
            ->setParameter('val', $value)
         
            ->getQuery()
            ->getResult()
    
        ;
    }



}

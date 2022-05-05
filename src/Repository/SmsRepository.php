<?php

namespace App\Repository;

use App\Entity\Sms;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Sms|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sms|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sms[]    findAll()
 * @method Sms[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SmsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sms::class);
    }

    // /**
    //  * @return Sms[] Returns an array of Sms objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Sms
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */



    public function findBySms($value)
    {
        return $this->createQueryBuilder('sms')
            ->andWhere('sms.emetteur = :val')
            ->andWhere('sms.recepteur = :value')
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
        return $this->createQueryBuilder('sms')
            ->andWhere('sms.recepteur = :value')
            ->setParameter('value', $value)
         
            ->getQuery()
            ->getResult()
    
        ;
    }
    public function findByRecepteur($value)
    {
        return $this->createQueryBuilder('sms')
            ->andWhere('sms.emetteur = :val')
            ->setParameter('val', $value)
         
            ->getQuery()
            ->getResult()
    
        ;
    }

}

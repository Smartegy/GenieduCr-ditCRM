<?php

namespace App\Repository;

use App\Entity\Modeleemail;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Modeleemail|null find($id, $lockMode = null, $lockVersion = null)
 * @method Modeleemail|null findOneBy(array $criteria, array $orderBy = null)
 * @method Modeleemail[]    findAll()
 * @method Modeleemail[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ModeleemailRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Modeleemail::class);
    }

    // /**
    //  * @return Modeleemail[] Returns an array of Modeleemail objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Modeleemail
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */


       
    public function findOneById($value): ?Modeleemail
    {
        return $this->createQueryBuilder('Modeleemail')
            ->andWhere('Modeleemail.id = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }



 
    public function findByMail($value)
    {
        return $this->createQueryBuilder('Modeleemail')
            ->Where('Modeleemail.mail = :value')
            ->setParameter('value', $value)
            
     
    
        ;
    }
    public function findBySms($value)
    {
        return $this->createQueryBuilder('Modeleemail')
            ->Where('Modeleemail.sms = :value')
            ->setParameter('value', $value)
            
     
    
        ;
    }



    
}

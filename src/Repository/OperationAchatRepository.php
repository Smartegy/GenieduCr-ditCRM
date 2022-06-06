<?php

namespace App\Repository;

use App\Entity\OperationAchat;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method OperationAchat|null find($id, $lockMode = null, $lockVersion = null)
 * @method OperationAchat|null findOneBy(array $criteria, array $orderBy = null)
 * @method OperationAchat[]    findAll()
 * @method OperationAchat[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OperationAchatRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OperationAchat::class);
    }

    // /**
    //  * @return OperationAchat[] Returns an array of OperationAchat objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?OperationAchat
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function findOperationsByLead($value){

        return $this->createQueryBuilder('OperationAchat')
        ->addSelect('OperationAchat')   
        ->innerjoin('OperationAchat.leads', 'l')  
       ->where('l.id = :val')
       ->setParameter('val', $value)
        ->getQuery()
        ->getResult()
        ;
    }

    public function findOperationsAchatByLead($value){

        return $this->createQueryBuilder('OperationAchat')
        ->addSelect('OperationAchat')   
        ->innerjoin('OperationAchat.leads', 'l')  
       ->where('l.id = :val')
       ->andWhere('OperationAchat.prix_vente is null')
       ->setParameter('val', $value)
        ->getQuery()
        ->getResult()
        ;
    }


    public function findOperationsVenteByLead($value){

        return $this->createQueryBuilder('OperationAchat')
        ->addSelect('OperationAchat')   
        ->innerjoin('OperationAchat.leads', 'l')  
       ->where('l.id = :val')
       ->andWhere('OperationAchat.prix_vente is not null')
       ->andWhere('OperationAchat.prix_achat is null')                     
       ->setParameter('val', $value)
        ->getQuery()
        ->getResult()
        ;
    }
    public function findOperationsEchangeByLead($value){

        return $this->createQueryBuilder('OperationAchat')
        ->addSelect('OperationAchat')   
        ->innerjoin('OperationAchat.leads', 'l')  
       ->where('l.id = :val')
       ->andWhere('OperationAchat.prix_achat is not null')
       ->andWhere('OperationAchat.prix_vente is not null')
       ->setParameter('val', $value)
        ->getQuery()
        ->getResult()
        ;
    }


}

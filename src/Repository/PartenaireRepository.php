<?php
namespace App\Repository;
use App\Entity\Partenaire;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
/**
 * @method Partenaire|null find($id, $lockMode = null, $lockVersion = null)
 * @method Partenaire|null findOneBy(array $criteria, array $orderBy = null)
 * @method Partenaire[]    findAll()
 * @method Partenaire[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PartenaireRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Partenaire::class);
    }
    // /**
    //  * @return Partenaire[] Returns an array of Partenaire objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */
    /*
    public function findOneBySomeField($value): ?Partenaire
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function findPartenairebyAgent($value){
        return $this->createQueryBuilder('partenaire')
        ->addSelect('partenaire') 
        ->innerjoin('partenaire.agent', 'pg')   
       ->where('p.id = :val')
       
       ->setParameter('val', $value)
        ->setParameter('agent', 'Agent')
        ->getQuery()
        ->getResult()
        ;
    }


   /* public function findNom(){
    {
        return $this->createQueryBuilder('partenaire')
        ->addSelect('nomutilisateur') 
        ->innerjoin('partenaire.utilisateur', 'pu') 
        ->where('partenaire.id = :pu.id')
        ->setParameter('utilisateur', 'Utilisateur')    
        ->getQuery()
        ->getResult()

        ;
    }*/


    public function findIdByUtilisateur($value)
    {
        return $this->createQueryBuilder('c')
            ->innerJoin('c.utilisateur', 'a')
            ->where('a.id = :idcons')
            ->setParameter('idcons', $value)
            ->getQuery()
            ->getResult()
            ;
    }
    
    public function findByAgent($value)
    {
        return $this->createQueryBuilder('c')
            ->innerJoin('c.agents', 'b')
            ->andwhere('b.id = :idcons')
            ->setParameter('idcons', $value)
            ->getQuery()
            ->getResult()
            ;
    }
    
    public function findByVendeur($value)
    {
        return $this->createQueryBuilder('c')
            ->innerJoin('c.vendeurrs', 'b')
            ->andwhere('b.id = :idcons')
            ->setParameter('idcons', $value)
            ->getQuery()
            ->getResult()
            ;
    }
    
}
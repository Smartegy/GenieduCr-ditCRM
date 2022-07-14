<?php

namespace App\Repository;

use App\Entity\Leads;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Leads|null find($id, $lockMode = null, $lockVersion = null)
 * @method Leads|null findOneBy(array $criteria, array $orderBy = null)
 * @method Leads[]    findAll()
 * @method Leads[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LeadsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Leads::class);
    }

    // /**
    //  * @return Leads[] Returns an array of Leads objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Leads
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    */

      public function findOneById($value): ?Leads
    {
        return $this->createQueryBuilder('lead')
            ->andWhere('lead.id = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    public function findOneByCourriel($value): ?Leads
    {
        return $this->createQueryBuilder('lead')
            ->andWhere('lead.courriel = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    } 
 


    public function findBytodaysrem()
    { $date = new \DateTime('@'.strtotime('now')) ;
         $time = date('d/m/Y');
        return $this->createQueryBuilder('l')
            ->andWhere('l.rappel = :val')
            ->setParameter('val',$time)
            
         
            ->getQuery()
            ->getResult()
        ;

      
    }

    public function findAllRem1()
    {  

       $qb= $this->select('u.rappel')
        ->from('leads', 'u')
         ->orderBy('u.name', 'ASC');
     
        ;
      return $qb ;
        

      
    }

    /**
     * @return Array[] Returns an array of Leads objects
     */
    
    public function findAllRem()
    {  

        return $this->createQueryBuilder('l')
        ->select('l.rappel'  )
        ->getQuery()
        ->getResult()
    ;
        
        

      
    }


   /* public function findachatagent($nom) 
    {
        return $this->createQueryBuilder('lead')
            ->andWhere('lead.type = :val')
            ->andWhere('lead.isCLient = :val2')
            ->andWhere('lead.agent = valagent')
            ->orWhere('lead.concessionnaire = valcons')
            ->orWhere('lead.marchand = valmar')
            ->orWhere('lead.partenaire = valpart')

            ->setParameter('val','1')
            ->setParameter('val2','0')
            ->orsetParameter('valagent',$nom) 
            ->orsetParameter('valcons',$nom)
            ->getQuery()
            ->getResult()
        ;
    }
*/
public function findachatByActeur($value,$param,$where) 
{
    return $this->createQueryBuilder('lead')
        ->andWhere('lead.type = :val')
        ->andWhere('lead.isCLient = :val2')
        ->andWhere($where)
        ->setParameter('val','1')
        ->setParameter('val2','0')
        ->setParameter($param,$value) 
        ->getQuery()
        ->getResult()
    ;
}
public function findventeByActeur($value,$param,$where) 
{
    return $this->createQueryBuilder('lead')
        ->andWhere('lead.type = :val')
        ->andWhere('lead.isCLient = :val2')
        ->andWhere($where)
        ->setParameter('val','0')
        ->setParameter('val2','0')
        ->setParameter($param,$value) 
        ->orderBy('lead.datecreation', 'DESC')
        ->getQuery()
        ->getResult()
    ;
}
    public function findachat() 
    {
        return $this->createQueryBuilder('lead')
            ->andWhere('lead.type = :val')
            ->andWhere('lead.isCLient = :val2')
            ->setParameter('val','1')
            ->setParameter('val2','0')
            ->orderBy('lead.datecreation', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }
    public function findvente()
    {
        return $this->createQueryBuilder('lead')
            ->andWhere('lead.type = :val')
            ->andWhere('lead.isCLient = :val2')
            ->setParameter('val','0')
            ->setParameter('val2','0')
            ->orderBy('lead.datecreation', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }


   public function findClientachatByActeur($value,$param,$where) 
    {
         return $this->createQueryBuilder('lead')
            ->andWhere('lead.type = :val')
            ->andWhere('lead.isCLient = :val2')
            ->andWhere($where)
            ->setParameter('val','1')
            ->setParameter('val2','1')
            ->setParameter($param,$value) 
            ->orderBy('lead.datecreation', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findClientachat() 
    {
         return $this->createQueryBuilder('lead')
            ->andWhere('lead.type = :val')
            ->andWhere('lead.isCLient = :val2')
            ->setParameter('val','1')
            ->setParameter('val2','1')
            ->orderBy('lead.datecreation', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }
    public function findClientvente()
    {
       return $this->createQueryBuilder('lead')
            ->andWhere('lead.type = :val')
            ->andWhere('lead.isCLient = :val2')
            ->setParameter('val','0')
            ->setParameter('val2','1')
            ->orderBy('lead.datecreation', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }
    public function findClientventeByActeur($value,$param,$where)
    {
       return $this->createQueryBuilder('lead')
            ->andWhere('lead.type = :val')
            ->andWhere('lead.isCLient = :val2')
            ->andWhere($where)
            ->setParameter('val','0')
            ->setParameter('val2','1')
            ->setParameter($param,$value) 
            ->orderBy('lead.datecreation', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }



}

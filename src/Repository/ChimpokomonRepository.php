<?php

namespace App\Repository;

use App\Entity\Chimpokomon;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Chimpokomon>
 */
class ChimpokomonRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Chimpokomon::class);
    }

       /**
        * @return Chimpokomon[] Returns an array of Chimpokomon objects
        */
       public function findStatusOn(): array
       {
           return $this->createQueryBuilder('c')
               ->andWhere('c.status = :val')
               ->setParameter('val', "on")
               ->getQuery()
               ->getResult()
           ;
       }

    //    public function findOneBySomeField($value): ?Chimpokomon
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}

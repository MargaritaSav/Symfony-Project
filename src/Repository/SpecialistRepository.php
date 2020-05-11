<?php

namespace App\Repository;

use App\Entity\Specialist;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Specialist|null find($id, $lockMode = null, $lockVersion = null)
 * @method Specialist|null findOneBy(array $criteria, array $orderBy = null)
 * @method Specialist[]    findAll()
 * @method Specialist[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SpecialistRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Specialist::class);
    }

     /**
      * @return Specialist[] Returns an array of Specialist objects
      */
    
   /* public function findByAppointmets()
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.appointments != :val')
            ->setParameter('val', null)
            ->orderBy('s.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }*/
    

    /*
    public function findOneBySomeField($value): ?Specialist
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

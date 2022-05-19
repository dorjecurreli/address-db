<?php

namespace App\Repository;

use App\Entity\PersonLabel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PersonLabel|null find($id, $lockMode = null, $lockVersion = null)
 * @method PersonLabel|null findOneBy(array $criteria, array $orderBy = null)
 * @method PersonLabel[]    findAll()
 * @method PersonLabel[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PersonLabelRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PersonLabel::class);
    }

    // /**
    //  * @return PersonLabel[] Returns an array of PersonLabel objects
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
    public function findOneBySomeField($value): ?PersonLabel
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

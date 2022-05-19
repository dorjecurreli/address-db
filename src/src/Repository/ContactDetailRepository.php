<?php

namespace App\Repository;

use App\Entity\ContactDetail;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ContactDetail|null find($id, $lockMode = null, $lockVersion = null)
 * @method ContactDetail|null findOneBy(array $criteria, array $orderBy = null)
 * @method ContactDetail[]    findAll()
 * @method ContactDetail[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContactDetailRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ContactDetail::class);
    }

    // /**
    //  * @return ContactDetail[] Returns an array of ContactDetail objects
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
    public function findOneBySomeField($value): ?ContactDetail
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

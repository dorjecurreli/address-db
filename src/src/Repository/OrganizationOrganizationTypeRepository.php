<?php

namespace App\Repository;

use App\Entity\OrganizationOrganizationType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method OrganizationOrganizationType|null find($id, $lockMode = null, $lockVersion = null)
 * @method OrganizationOrganizationType|null findOneBy(array $criteria, array $orderBy = null)
 * @method OrganizationOrganizationType[]    findAll()
 * @method OrganizationOrganizationType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrganizationOrganizationTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrganizationOrganizationType::class);
    }

    // /**
    //  * @return OrganizationOrganizationType[] Returns an array of OrganizationOrganizationType objects
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
    public function findOneBySomeField($value): ?OrganizationOrganizationType
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

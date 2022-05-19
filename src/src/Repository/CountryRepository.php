<?php

namespace App\Repository;

use App\Entity\Country;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Country|null find($id, $lockMode = null, $lockVersion = null)
 * @method Country|null findOneBy(array $criteria, array $orderBy = null)
 * @method Country[]    findAll()
 * @method Country[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CountryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Country::class);
    }

    //
    // @return Country[] Returns an array of Country objects
    //
    public function findAllWithOrganizationCount()
    {
        $query = $this->createQueryBuilder('c')
            ->select( 'c.name, count(o.id) AS OrgnaizationCounter')
            ->innerJoin('c.addresses', 'a')
            ->innerJoin('a.organizations', 'o')
            ->groupBy('c.name')
            ->getQuery()
            ->getArrayResult()
        ;

        return $query;
    }


    /*
    public function findOneBySomeField($value): ?Country
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

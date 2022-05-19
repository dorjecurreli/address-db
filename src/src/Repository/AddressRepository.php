<?php

namespace App\Repository;

use App\Entity\Address;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Address|null find($id, $lockMode = null, $lockVersion = null)
 * @method Address|null findOneBy(array $criteria, array $orderBy = null)
 * @method Address[]    findAll()
 * @method Address[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AddressRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Address::class);
    }


    //@return Regions[] Returns an array of Regions objects
    public function getDistinctRegions()
    {
        $query = $this->createQueryBuilder('a')
            ->select('a.region')->distinct();

        return $query->getQuery()->getSingleColumnResult();

    }



    public function getDistinctCountries()
    {
        $query = $this->createQueryBuilder('a')
            ->select('a.country')->distinct();

        return $query->getQuery()->getSingleColumnResult();
    }

    //
    // @return Address[] Returns an array of Address objects
    //
//    public function findByExampleField()
//    {
//        $query = $this->createQueryBuilder('a')
//            ->select('COUNT(o.id) AS organizationCount, a.country')
//            ->innerJoin('a.organizations', 'o')
//            ->innerJoin('a.country', 'c')
//            ->groupBy('c.name')
//            ->getQuery()
//            ->getResult()
////            ->getArrayResult()
//        ;
//        //dd($query);
//        return $query;
//    }


    /*
    public function findOneBySomeField($value): ?Address
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

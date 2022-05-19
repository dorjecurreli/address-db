<?php

namespace App\Repository;

use App\Entity\Label;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Label|null find($id, $lockMode = null, $lockVersion = null)
 * @method Label|null findOneBy(array $criteria, array $orderBy = null)
 * @method Label[]    findAll()
 * @method Label[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LabelRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Label::class);
    }


    /**
     * Find labels from search bar input
     *
     * @return Label[] Returns an array of User objects
     */
    public function findBySearchQuery($input)
    {

        return $this->createQueryBuilder('label')
            ->andWhere('label.name LIKE :input')
            ->setParameter('input', '%' . $input . '%')
            ->orderBy('label.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
            ;
    }







}

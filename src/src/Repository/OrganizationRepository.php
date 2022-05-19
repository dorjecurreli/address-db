<?php

namespace App\Repository;

use App\Entity\Organization;
use App\Entity\OrganizationType;
use App\Jobs\FilterSearch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;


/**
 * @method Organization|null find($id, $lockMode = null, $lockVersion = null)
 * @method Organization|null findOneBy(array $criteria, array $orderBy = null)
 * @method Organization[]    findAll()
 * @method Organization[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrganizationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Organization::class);
    }


    /**
     * Find organizations from search bar input
     *
     * @return Organization[] Returns an array of User objects
     */
    public function findBySearchQuery($input)
    {

        return $this->createQueryBuilder('o')
            ->andWhere('o.name LIKE :input')
            ->setParameter('input', '%' . $input . '%')
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();

    }

    /**
     * Find organizations by country
     *
     * @param $inputs array
     * @return Organization[] Returns an array of User objects
     */
    public function findByCountry($inputs)
    {
        $query = $this->createQueryBuilder('o')
            ->innerJoin('o.address', 'a')
            ->innerJoin('a.country', 'c')
            ->orderBy('o.id', 'ASC');

        $where = [];
        foreach ($inputs as $key => $value) {
            $where[] = 'c.id = :id' . '_' . $key;
            $parameters[] = $value;
        }

        $query->andWhere(implode(' OR ', $where));

        foreach ($parameters as $key => $value) {
            $query->setParameter('id' . '_' . $key, $value);
        }

        $result = $query->setMaxResults(10)->getQuery()->getResult();

        //dd($result);
        return $result;
    }

    /**
     * Find organizations by region
     *
     * @param $inputs array
     * @return Organization[] Returns an array of User objects
     */
    public function findByRegion($inputs)
    {
        $query = $this->createQueryBuilder('o')
            ->innerJoin('o.address', 'a')
            ->orderBy('o.id', 'ASC');

        $where = [];
        foreach ($inputs as $key => $value) {
            $where[] = 'a.region = :region' . '_' . $key;
            $parameters[] = $value;
        }

        $query->andWhere(implode(' OR ', $where));

        foreach ($parameters as $key => $value) {
            $query->setParameter('region' . '_' . $key, $value);
        }

        $result = $query->getQuery()->getResult();

        return $result;
    }


   public function findAllWithPaginator($field = 'id', $order = 'ASC', $number = 30)
   {
       $query = $this->createQueryBuilder('o')
           ->innerJoin('o.address', 'a')
           ->orderBy('o.' . $field, $order)
           ->getQuery();


       $paginator = new Paginator($query);
       $totalItems = count($paginator);
       $currentPage = 1;
       $pageSize = 30;
       $totalPageCount = ceil($totalItems / $pageSize);
       $nextPage = (($currentPage < $totalPageCount) ? $currentPage + 1 : $totalPageCount);
       $previousPage = (($currentPage > 1 ) ? $currentPage - 1 : 1);

       //dd($totalPageCount);
       $records = $paginator
           ->getQuery()
           ->setFirstResult($pageSize * $currentPage)
           ->setMaxResults($pageSize)
           ->getResult();




       return ['data' => $records, 'total_pages' => $totalPageCount];

   }


    /**
     * @param FilterSearch $filterSearch
     * @return int|mixed|string
     */
    public function findByFilterSearch(FilterSearch $filterSearch)
    {
        $qb = $this->createQueryBuilder('o')
            ->where('0 = 0');

        $filterSearch->on("Name", function (array $values) use ($qb) {
            foreach ($values as $value) {
                $qb->andWhere("o.name LIKE :name")->setParameter('name', '%' . $value . '%');
            }
        });

        $filterSearch->on("Country", function (array $values) use ($qb) {
            dump("from repo", $values);
            foreach ($values as $value) {
                $qb ->innerJoin('o.address', 'address')
                    ->andWhere("address.country = :country")->setParameter('country', $value);
            }
        });

        $filterSearch->on("Region", function (array $values) use ($qb) {
            dump("from repo", $values);
            foreach ($values as $value) {
                $qb ->innerJoin('o.address', 'address')
                    ->andWhere("address.region = :region")->setParameter('region', $value);
            }
        });

        $filterSearch->on("Organization Type", function (array $values) use ($qb) {
            foreach ($values as $value) {
                $organizationTypes = $this->getEntityManager()->getRepository(OrganizationType::class)->findBy(['name' => $value]);
                foreach ($organizationTypes as $organizationType) {
                    $qb ->innerJoin('o.organizationOrganizationType', 'organizationOrganizationType')
                        ->andWhere("organizationOrganizationType.organizationType = :organizationType")->setParameter('organizationType', $organizationType->getId());
                }
            }
        });

        $filterSearch->on("Persons", function (array $values) use ($qb) {
            dump($values);
            foreach ($values as $value) {
                dump($value);
                $qb ->innerJoin('o.person', 'person')
                    ->andWhere("CONCAT(person.firstName, ' ', person.lastName ) LIKE :query")->setParameter('query', '%' . $value . '%');
            }
        });

        $filterSearch->on("Address", function (array $values) use ($qb) {
            dump($values);
            foreach ($values as $value) {
                dump($value);
                $qb ->innerJoin('o.address', 'address')
                    ->andWhere("CONCAT(address.country, ' ' , address.region, ' ', address.city, ' ', address.houseNumber, ' ',  address.postalCode) LIKE :query")->setParameter('query', '%' . $value . '%');
            }
        });

        $filterSearch->on("Contact Details", function (array $values) use ($qb) {
            dump($values);
            foreach ($values as $value) {
                dump($value);
                $qb ->innerJoin('o.contactDetail', 'contactDetail')
                    -> andWhere("CONCAT(contactDetail.telephoneNumber, ' ', contactDetail.fax, ' ', contactDetail.internetSite, ' ',  contactDetail.email) LIKE :query")->setParameter('query', '%' . $value . '%');
            }
        });

        return $qb->getQuery()->getResult();
    }

}

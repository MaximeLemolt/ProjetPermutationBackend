<?php

namespace App\Repository;

use App\Entity\Annonce;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Annonce|null find($id, $lockMode = null, $lockVersion = null)
 * @method Annonce|null findOneBy(array $criteria, array $orderBy = null)
 * @method Annonce[]    findAll()
 * @method Annonce[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnnonceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Annonce::class);
    }

    // /**
    //  * @return Annonce[] Returns an array of Annonce objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Annonce
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    /**
     * Fetch all ads with city, grade, service, user (DQL)
     */
    public function fetchAllAdsJoinedWithAllRelations()
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT a, g, s, c, m, u
            FROM App\Entity\Annonce a
            INNER JOIN a.grade g
            INNER JOIN a.service s
            INNER JOIN a.city c
            INNER JOIN a.mutationCity m
            INNER JOIN a.user u
            ORDER BY a.createdAt DESC'
        );

        return $query->getResult();
    }

    /**
     * Fetch all ads with city, grade, service, user (Qb)
     */
    public function fetchAllAdsJoinedWithAllRelationsQb()
    {
        // Requête de base                    
        return $this->createQueryBuilder('a')
                    ->join('a.grade', 'g')
                    ->join('a.service', 's')
                    ->join('a.city', 'c')
                    ->join('a.mutationCity', 'm')
                    ->join('a.mutationDepartment', 'd')
                    ->join('a.mutationRegion', 'r')
                    ->join('a.user', 'u')
                    ->addSelect('g,s,c,m,u,d,r')
                    ->andWhere('a.status = 1')
                    ->orderBy('a.createdAt', 'DESC');
    }

    /**
     * Fetch all ads and sort by grade, city, cityMutation
     */
    public function searchAds(
        $searchGrade = null, 
        $searchCity = null, 
        $searchCityMutation = null, 
        $searchDepartmentMutation = null, 
        $searchRegionMutation = null
        )
    {
        $query = $this->fetchAllAdsJoinedWithAllRelationsQb();

        if($searchGrade != null){
            $query = $query
                ->andwhere('g.name LIKE :grade')
                ->setParameter('grade', $searchGrade . '%');
        }

        if($searchCity != null){
            $query = $query
                ->andwhere('c.name LIKE :cityName')
                ->setParameter('cityName', $searchCity . '%');
        }

        if($searchCityMutation != null){
            $query = $query
                ->andwhere('m.name LIKE :cityMutation')
                ->setParameter('cityMutation', $searchCityMutation . '%');
        }

        if($searchDepartmentMutation != null){
            $query = $query
                ->andwhere('d.name LIKE :mutationDepartment')
                ->setParameter('mutationDepartment', $searchDepartmentMutation . '%');
        }

        if($searchRegionMutation != null){
            $query = $query
                ->andwhere('r.name LIKE :mutationRegion')
                ->setParameter('mutationRegion', $searchRegionMutation . '%');
        }

        return $query->getQuery()->getResult();
    }

    /**
     * Fetch one ad with city, grade, service, user (Qb)
     */
    public function fetchOneAdJoinedWithAllRelations($id)
    {
        // Requête de base                    
        $query = $this->createQueryBuilder('a')
                    ->join('a.grade', 'g')
                    ->join('a.service', 's')
                    ->join('a.city', 'c')
                    ->join('a.mutationCity', 'm')
                    ->join('a.user', 'u')
                    ->addSelect('g,s,c,m,u')
                    ->andWhere('a.id = :id')
                    ->setParameter('id', $id)
                    ->orderBy('a.createdAt', 'DESC')
                    ->getQuery()
                    ->getResult();

        return !empty($query) ? $query : null;
    }
}

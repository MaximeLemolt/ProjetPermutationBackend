<?php

namespace App\Repository;

use App\Entity\TokenBlacklist;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TokenBlacklist|null find($id, $lockMode = null, $lockVersion = null)
 * @method TokenBlacklist|null findOneBy(array $criteria, array $orderBy = null)
 * @method TokenBlacklist[]    findAll()
 * @method TokenBlacklist[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TokenBlacklistRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TokenBlacklist::class);
    }

    // /**
    //  * @return TokenBlacklist[] Returns an array of TokenBlacklist objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TokenBlacklist
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    /**
     * Remove the blacklisted token if it has expired
     */
    public function deleteExpiratedToken()
    {
        return $this->createQueryBuilder('t')
                    ->delete('App\Entity\TokenBlacklist', 't')
                    ->where('t.expiration < CURRENT_DATE()')
                    ->getQuery()
                    ->getResult();
    }
}

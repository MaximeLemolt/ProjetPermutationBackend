<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(UserInterface $user, string $newEncodedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newEncodedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }

    // /**
    //  * @return User[] Returns an array of User objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    /**
     * Get all users contacted
     */
    public function getUsersContactedByUser($author)
    {
        return $this->createQueryBuilder('u')
                    ->join('u.receivedMessage', 'm')
                    ->andWhere('m.author = :author')
                    ->setParameter('author', $author)
                    ->getQuery()
                    ->getResult();
    }

    /**
     * Get all users who contacted me
     */
    public function getUsersWhoContatedTheUser($author)
    {
        return $this->createQueryBuilder('u')
                    ->join('u.sendedMessage', 'm')
                    ->andWhere('m.recipient = :author')
                    ->setParameter('author', $author)
                    ->getQuery()
                    ->getResult();
    }

    /**
     * Find all user by city and mutation city
     */
    public function findUserByCityAndMutationCity($currentCity, $mutationCity)
    {
        return $this->createQueryBuilder('u')
                    ->join('u.annonce', 'a')
                    ->join('a.city', 'c')
                    ->join('a.mutationCity', 'm')
                    ->andWhere('c.name = :currentCity AND m.name = :mutationCity')
                    ->setParameter('currentCity', $currentCity)
                    ->setParameter('mutationCity', $mutationCity)
                    ->getQuery()
                    ->getResult();
    }
}

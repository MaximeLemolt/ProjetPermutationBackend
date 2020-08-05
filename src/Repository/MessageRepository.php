<?php

namespace App\Repository;

use App\Entity\Message;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Message|null find($id, $lockMode = null, $lockVersion = null)
 * @method Message|null findOneBy(array $criteria, array $orderBy = null)
 * @method Message[]    findAll()
 * @method Message[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MessageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Message::class);
    }

    // /**
    //  * @return Message[] Returns an array of Message objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Message
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function getMessagesBetweenTwoUsers($author, $recipient)
    {
        return $this->createQueryBuilder('m')
                    ->join('m.author', 'a')
                    ->join('m.recipient', 'r')
                    ->addSelect('m, a, r')
                    ->andWhere('(m.author = :author OR m.author = :recipient) AND (m.recipient = :author OR m.recipient = :recipient)')
                    ->setParameter('author', $author)
                    ->setParameter('recipient', $recipient)
                    ->orderBy('m.createdAt', 'ASC')
                    ->getQuery()
                    ->getResult();
    }

    public function getRecipientByAuthor($author)
    {
        return $this->createQueryBuilder('m', 'm.recipient')
                    ->where('m.author = :author')
                    ->setParameter('author', $author)
                    ->getQuery()
                    ->getResult();
    }
}


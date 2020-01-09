<?php

namespace App\Repository;

use App\Entity\Authors;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Authors|null find($id, $lockMode = null, $lockVersion = null)
 * @method Authors|null findOneBy(array $criteria, array $orderBy = null)
 * @method Authors[]    findAll()
 * @method Authors[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AuthorsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Authors::class);
    }

    /**
     * @return Authors[] Returns an array of Authors objects
     */
    public function deleteByUserId($value)
    {
        return $this->createQueryBuilder('a')
            ->delete()
            ->where('a.author_id = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return Authors[] Returns an array of Authors objects
     */
    public function deleteByPublicationId($value)
    {
        return $this->createQueryBuilder('a')
            ->delete()
            ->where('a.publication_id = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return Authors[] Returns an array of Authors objects
     */
    public function deleteByPublicationIdWithoutAuthor($value)
    {
        $qb = $this->createQueryBuilder('a')
            ->delete()
            ->where('a.publication_id = :val')
            ->setParameter('val', $value)
            ->andWhere('a.is_author = :isa')
            ->setParameter('isa', 0);
        $query = $qb->getQuery();
        // var_dump($query->getDQL());
        // die;
        return $query->getResult();
    }
}

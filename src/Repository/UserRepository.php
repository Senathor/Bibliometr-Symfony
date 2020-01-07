<?php

namespace App\Repository;

use App\Entity\Publication;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * @return Publication[] Returns an array of User objects
     */
    public function findByPublicationsID($value)
    {
        return $this->createQueryBuilder('p')
            ->where(':val MEMBER OF p.publications')
            ->setParameter('val', $value)
            ->getQuery()
            ->getResult()
        ;
    }
}

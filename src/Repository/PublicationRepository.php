<?php

namespace App\Repository;

use App\Entity\Publication;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Publication|null find($id, $lockMode = null, $lockVersion = null)
 * @method Publication|null findOneBy(array $criteria, array $orderBy = null)
 * @method Publication[]    findAll()
 * @method Publication[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PublicationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Publication::class);
    }

    /**
     * @return User[] Returns an array of User objects
     */
    public function findByUserID($value)
    {
        return $this->createQueryBuilder('u')
            ->where(':val MEMBER OF u.users')
            ->setParameter('val', $value)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return Publication[] Returns an array of User objects
     */
    public function findByCrit($value)
    {
        $title = isset($value->title) ? $value->title : null;
        $authors = isset($value->authors) ? $value->authors : null;
        $shares = isset($value->shares) ? $value->shares : null;
        $points = isset($value->points) ? $value->points : null;
        $magazine = isset($value->magazine) ? $value->magazine : null;
        $conference = isset($value->conference) ? $value->conference : null;
        $url = isset($value->url) ? $value->url : null;
        $data_od = isset($value->data_od) ? $value->data_od : null;
        $data_do = isset($value->data_do) ? $value->data_do : null;
        $sort = isset($value->sort) ? $value->sort : null;
        $order = isset($value->order) ? $value->order : null;
        $ids = isset($value->ids) ? $value->ids : null;

        $qb = $this->createQueryBuilder('p')->select('p');

        if ($title) {
            $qb->andWhere("p.title LIKE :title")->setParameter('title', '%' . $title . '%');
        }
        if ($authors) {
            $qb->andWhere("p.authors LIKE :authors")->setParameter('authors', '%' . $authors . '%');
        }
        if ($shares) {
            $qb->andWhere("p.shares LIKE :shares")->setParameter('shares', '%' . $shares . '%');
        }
        if ($points) {
            $qb->andWhere("p.points = :points")->setParameter('points', $points);
        }
        if ($magazine) {
            $qb->andWhere("p.magazine = :magazine")->setParameter('magazine', $magazine);
        }
        if ($conference) {
            $qb->andWhere("p.conference = :conference")->setParameter('conference', $conference);
        }
        if ($url) {
            $qb->andWhere("p.url = :url")->setParameter('url', $url);
        }
        if ($data_od) {
            $qb->andWhere("p.data_od > :data_od")->setParameter('data_od', $data_od);
        }
        if ($data_do) {
            $qb->andWhere("p.data_do < :data_do")->setParameter('data_do', $data_do);
        }
        if ($ids) {
            $qb->andWhere(
                $qb->expr()->in('p.id', $ids)
            );
        }

        if ($order) {
            if ($sort) {
                $qb->addOrderBy("p.{$order}", $sort);
            } else {
                $qb->addOrderBy("p.{$order}", "ASC");
            }
        }

        $query = $qb->getQuery();
        // var_dump($query->getDQL());
        // die;
        return $query->getResult();
    }
}

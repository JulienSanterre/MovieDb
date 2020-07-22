<?php

namespace App\Repository;

use App\Entity\Genre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Genre|null find($id, $lockMode = null, $lockVersion = null)
 * @method Genre|null findOneBy(array $criteria, array $orderBy = null)
 * @method Genre[]    findAll()
 * @method Genre[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GenreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Genre::class);
    }

    /**
    * @return Genre[] Returns an array of Genre objects
    */
    public function findAllByName()
    {
        return $this->createQueryBuilder('g')
            ->orderBy('g.name', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
    * @return Genre[] Returns an array of Genre objects
    */
    public function isFound(string $name)
    {

        $result = $this->createQueryBuilder('g')
        ->where('g.name = :name')
        ->setParameter('name', $name)
        ->getQuery()
        ->getResult();

        return $result;
    }

    /**
    * @return Genre Returns an array of Genre objects
    */
    public function findByApiId($apiId)
    {
        $result = $this->createQueryBuilder('g')
        ->where('g.apiId = :apiId')
        ->setParameter('apiId', $apiId)
        ->getQuery()
        ->getResult();

        return $result[0];
    }
}

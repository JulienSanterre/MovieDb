<?php

namespace App\Repository;

use App\Entity\Movie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Movie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Movie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Movie[]    findAll()
 * @method Movie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MovieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Movie::class);
    }

    /**
    * @return Movie[] Returns an array of Movie objects
    */
    public function findAllByTitle()
    {
        return $this->createQueryBuilder('m')
            ->orderBy('m.title', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
    * @return Movie[] Returns an array of Movie objects
    */
    public function isFound(int $apiMovieId)
    {

        $result = $this->createQueryBuilder('m')
        ->where('m.apiMovieId = :apiMovieId')
        ->setParameter('apiMovieId', $apiMovieId)
        ->getQuery()
        ->getResult();

        return $result;
    }
}

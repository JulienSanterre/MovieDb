<?php

namespace App\Repository;

use App\Entity\Casting;
use App\Entity\Movie;
use App\Entity\Person;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Casting|null find($id, $lockMode = null, $lockVersion = null)
 * @method Casting|null findOneBy(array $criteria, array $orderBy = null)
 * @method Casting[]    findAll()
 * @method Casting[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CastingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Casting::class);
    }

    /**
    * @return Department[] Returns an array of Department objects
    */
    public function findAllByName()
    {
        return $this->createQueryBuilder('c')
            ->orderBy('c.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findCastingWithMovie(Movie $movie)
    {
        $queryBuilder = $this->createQueryBuilder('c')
            ->addSelect('p')
            //->join('t.person', 'p')
            ->join('c.person', 'p')
            ->where('c.movie = :movie')
            ->orderBy('c.creditOrder')
            ->setParameter('movie', $movie)
        ;

        return $queryBuilder->getquery()->getResult();
    }

    /**
    * @return Casting[] Returns an array of Casting objects
    */
    public function findCastingWithPerson(Person $person)
    {
        $queryBuilder = $this->createQueryBuilder('c')
            ->addSelect('p')
            ->addSelect('m')
            //->join('t.person', 'p')
            ->join('c.person', 'p')
            ->join('c.movie', 'm')
            ->where('c.person = :person')
            ->orderBy('p.name')
            ->setParameter('person', $person)
        ;
        return $queryBuilder->getquery()->getResult();
    }
    

    /**
    * @return Casting[] Returns an array of Casting objects
    */
    public function findAllById()
    {
        return $this->createQueryBuilder('c')
            ->orderBy('c.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
    * @return Casting[] Returns an array of Casting objects
    */
    public function isFound(Person $apiPersonId)
    {
        $result = $this->createQueryBuilder('c')
        ->where('c.apiPersonId = :apiPersonId')
        ->setParameter('apiPersonId', $apiPersonId)
        ->getQuery()
        ->getResult();
        return $result;
    }
}

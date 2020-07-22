<?php

namespace App\Repository;

use App\Entity\Job;
use App\Entity\Movie;
use App\Entity\Person;
use App\Entity\Team;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Team|null find($id, $lockMode = null, $lockVersion = null)
 * @method Team|null findOneBy(array $criteria, array $orderBy = null)
 * @method Team[]    findAll()
 * @method Team[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TeamRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Team::class);
    }

    /**
    * @return Team[] Returns an array of Team objects
    */
    public function findAllById()
    {
        return $this->createQueryBuilder('t')
            ->orderBy('t.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findTeamWithMovie(Movie $movie)
    {
        $queryBuilder = $this->createQueryBuilder('t')
            ->addSelect('p')
            ->join('t.person', 'p')
            ->where('t.movie = :movie')
            ->OrderBy('p.name')
            ->setParameter('movie', $movie)
        ;

        return $queryBuilder->getquery()->getResult();
    }

    public function findTeamWithJob(Job $job)
    {
        $queryBuilder = $this->createQueryBuilder('t')
            ->addSelect('p')
            ->join('t.person', 'p')
            ->where('t.job = :job')
            ->setParameter('job', $job)
        ;

        return $queryBuilder->getquery()->getResult();
    }

    /**
    * @return Team[] Returns an array of Casting objects
    */
    public function findTeamWithPerson(Person $person)
    {
        $queryBuilder = $this->createQueryBuilder('t')
            ->addSelect('p')
            ->addSelect('j')
            ->join('t.person', 'p')
            ->join('t.job', 'j')
            ->join('t.movie', 'm')
            ->where('t.person = :person')
            ->orderBy('j.name')
            ->setParameter('person', $person)
        ;
        return $queryBuilder->getquery()->getResult();
    }

}

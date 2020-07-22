<?php

namespace App\Repository;

use App\Entity\Department;
use App\Entity\Job;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Job|null find($id, $lockMode = null, $lockVersion = null)
 * @method Job|null findOneBy(array $criteria, array $orderBy = null)
 * @method Job[]    findAll()
 * @method Job[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class JobRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Job::class);
    }

    /**
    * @return Job[] Returns an array of Job objects
    */
    public function findAllByName()
    {
        return $this->createQueryBuilder('j')
            ->orderBy('j.name', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
    * @return Job[] Returns an array of Movie objects
    */
    public function isFound(Department $department)
    {
        $result = $this->createQueryBuilder('j')
        ->where('j.department = :department')
        ->setParameter('department', $department)
        ->getQuery()
        ->getResult();
        if(isset ($result[0])){
            return $result[0];
        }
        return $result;
    }
}

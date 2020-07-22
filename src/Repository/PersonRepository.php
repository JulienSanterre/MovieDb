<?php

namespace App\Repository;

use App\Entity\Person;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Person|null find($id, $lockMode = null, $lockVersion = null)
 * @method Person|null findOneBy(array $criteria, array $orderBy = null)
 * @method Person[]    findAll()
 * @method Person[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PersonRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Person::class);
    }

    /**
    * @return Person[] Returns an array of Person objects
    */
    public function findAllByName()
    {

        return $this->createQueryBuilder('p')
            ->orderBy('p.name', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
    
    /**
    * @return Person[] Returns an array of Movie objects
    */
    public function isFound(int $apiId)
    {
        $result = $this->createQueryBuilder('p')
        ->where('p.apiId = :apiId')
        ->setParameter('apiId', $apiId)
        ->getQuery()
        ->getResult();
        if(isset ($result[0])){
            return $result[0];
        }
        return $result;
    }
}

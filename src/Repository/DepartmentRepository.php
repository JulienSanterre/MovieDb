<?php

namespace App\Repository;

use App\Entity\Department;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Department|null find($id, $lockMode = null, $lockVersion = null)
 * @method Department|null findOneBy(array $criteria, array $orderBy = null)
 * @method Department[]    findAll()
 * @method Department[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DepartmentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Department::class);
    }

    /**
    * @return Department[] Returns an array of Department objects
    */
    public function findAllByName()
    {
        return $this->createQueryBuilder('d')
            ->orderBy('d.name', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
    * @return Department[] Returns an array of Movie objects
    */
    public function isFound(string $name)
    {
        $result = $this->createQueryBuilder('d')
        ->where('d.name = :name')
        ->setParameter('name', $name)
        ->getQuery()
        ->getResult();
        if(isset ($result[0])){
            return $result[0];
        }
        return $result;
    }
}

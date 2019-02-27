<?php

namespace App\Repository;

use App\Entity\SupporterPerson;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method SupporterPerson|null find($id, $lockMode = null, $lockVersion = null)
 * @method SupporterPerson|null findOneBy(array $criteria, array $orderBy = null)
 * @method SupporterPerson[]    findAll()
 * @method SupporterPerson[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SupporterPersonRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, SupporterPerson::class);
    }

    // /**
    //  * @return SupporterPerson[] Returns an array of SupporterPerson objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?SupporterPerson
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

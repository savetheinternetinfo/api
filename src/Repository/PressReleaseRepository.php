<?php

namespace App\Repository;

use App\Entity\PressRelease;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PressRelease|null find($id, $lockMode = null, $lockVersion = null)
 * @method PressRelease|null findOneBy(array $criteria, array $orderBy = null)
 * @method PressRelease[]    findAll()
 * @method PressRelease[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PressReleaseRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PressRelease::class);
    }

    // /**
    //  * @return PressRelease[] Returns an array of PressRelease objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PressRelease
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

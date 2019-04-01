<?php

namespace App\Repository;

use App\Entity\TextVersion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method TextVersion|null find($id, $lockMode = null, $lockVersion = null)
 * @method TextVersion|null findOneBy(array $criteria, array $orderBy = null)
 * @method TextVersion[]    findAll()
 * @method TextVersion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TextVersionRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TextVersion::class);
    }

    // /**
    //  * @return TextVersion[] Returns an array of TextVersion objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TextVersion
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

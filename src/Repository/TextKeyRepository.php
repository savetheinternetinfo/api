<?php

namespace App\Repository;

use App\Entity\TextKey;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method TextKey|null find($id, $lockMode = null, $lockVersion = null)
 * @method TextKey|null findOneBy(array $criteria, array $orderBy = null)
 * @method TextKey[]    findAll()
 * @method TextKey[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TextKeyRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TextKey::class);
    }

    // /**
    //  * @return TextKey[] Returns an array of TextKey objects
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
    public function findOneBySomeField($value): ?TextKey
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

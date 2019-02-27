<?php

namespace App\Repository;

use App\Entity\TranslatedText;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method TranslatedText|null find($id, $lockMode = null, $lockVersion = null)
 * @method TranslatedText|null findOneBy(array $criteria, array $orderBy = null)
 * @method TranslatedText[]    findAll()
 * @method TranslatedText[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TranslatedTextRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TranslatedText::class);
    }

    // /**
    //  * @return TranslatedText[] Returns an array of TranslatedText objects
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
    public function findOneBySomeField($value): ?TranslatedText
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

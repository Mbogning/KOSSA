<?php

namespace App\Repository\Play;

use App\Entity\Play\TypeMusical;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method TypeMusical|null find($id, $lockMode = null, $lockVersion = null)
 * @method TypeMusical|null findOneBy(array $criteria, array $orderBy = null)
 * @method TypeMusical[]    findAll()
 * @method TypeMusical[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypeMusicalRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TypeMusical::class);
    }

    // /**
    //  * @return TypeMusical[] Returns an array of TypeMusical objects
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
    public function findOneBySomeField($value): ?TypeMusical
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

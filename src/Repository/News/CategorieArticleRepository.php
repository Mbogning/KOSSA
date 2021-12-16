<?php

namespace App\Repository\News;

use App\Entity\News\CategorieArticle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method CategorieArticle|null find($id, $lockMode = null, $lockVersion = null)
 * @method CategorieArticle|null findOneBy(array $criteria, array $orderBy = null)
 * @method CategorieArticle[]    findAll()
 * @method CategorieArticle[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategorieArticleRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CategorieArticle::class);
    }

    // /**
    //  * @return CategorieArticle[] Returns an array of CategorieArticle objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CategorieArticle
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

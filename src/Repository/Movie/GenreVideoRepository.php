<?php

namespace App\Repository\Movie;

use App\Entity\Movie\GenreVideo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method GenreVideo|null find($id, $lockMode = null, $lockVersion = null)
 * @method GenreVideo|null findOneBy(array $criteria, array $orderBy = null)
 * @method GenreVideo[]    findAll()
 * @method GenreVideo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GenreVideoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, GenreVideo::class);
    }

    // /**
    //  * @return GenreVideo[] Returns an array of GenreVideo objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?GenreVideo
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

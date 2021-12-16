<?php

namespace App\Repository\Event;

use App\Entity\Event\CategorieEvent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method CategorieEvent|null find($id, $lockMode = null, $lockVersion = null)
 * @method CategorieEvent|null findOneBy(array $criteria, array $orderBy = null)
 * @method CategorieEvent[]    findAll()
 * @method CategorieEvent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategorieEventRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CategorieEvent::class);
    }

    
   
}

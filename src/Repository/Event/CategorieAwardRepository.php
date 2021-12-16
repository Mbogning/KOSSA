<?php

namespace App\Repository\Event;

use App\Entity\Event\CategorieAward;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method CategorieAward|null find($id, $lockMode = null, $lockVersion = null)
 * @method CategorieAward|null findOneBy(array $criteria, array $orderBy = null)
 * @method CategorieAward[]    findAll()
 * @method CategorieAward[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategorieAwardRepository extends ServiceEntityRepository {

    public function __construct(RegistryInterface $registry) {
        parent::__construct($registry, CategorieAward::class);
    }

    public function findByCategorieUser(CategorieAward $cat, $user) {
        $qb = $this->createQueryBuilder('c')
                ->addSelect('a', 'u')
                ->innerJoin('c.artistes', 'a')
                ->leftJoin('a.users', 'u')
                ->where('c = :cat')
                ->andWhere('u = :user')
                ->setParameter('cat', $cat)
                ->setParameter('user', $user);
        return $qb->getQuery()->getOneOrNullResult();
    }
    
    public function findByCategorieGuest(CategorieAward $cat, $email) {
        $qb = $this->createQueryBuilder('c')
                ->addSelect('a', 'g')
                ->innerJoin('c.artistes', 'a')
                ->leftJoin('a.guests', 'g')
                ->where('c = :cat')
                ->andWhere('g.email = :email')
                ->setParameter('cat', $cat)
                ->setParameter('email', $email);
        return $qb->getQuery()->getOneOrNullResult();
    }

}

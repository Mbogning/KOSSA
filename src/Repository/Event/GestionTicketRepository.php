<?php

namespace App\Repository\Event;

use App\Entity\Event\GestionTicket;
use App\Entity\Event\CategorieEvent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method GestionTicket|null find($id, $lockMode = null, $lockVersion = null)
 * @method GestionTicket|null findOneBy(array $criteria, array $orderBy = null)
 * @method GestionTicket[]    findAll()
 * @method GestionTicket[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GestionTicketRepository extends ServiceEntityRepository {

    public function __construct(RegistryInterface $registry) {
        parent::__construct($registry, GestionTicket::class);
    }

    /**
     * @return Article[]
     */
    public function findTicketBySearchQuery($user, string $rawQuery, $limit = null, $offset = null) {
        $query = $this->sanitizeSearchQuery($rawQuery);
        $searchTerms = $this->extractSearchTerms($query);

        if (0 === \count($searchTerms)) {
            return [];
        }

        $queryBuilder = $this->createQueryBuilder('p')
                ->addSelect('t', 'e')
                ->innerJoin('p.user', 'u')
                ->leftJoin('p.ticket', 't')
                ->leftJoin('t.event', 'e')
                ->where('u =:user')
                ->orderBy('p.date', 'DESC')
                ->setParameter('user', $user);

        foreach ($searchTerms as $key => $term) {
            $queryBuilder
                    ->andWhere('e.titre LIKE :t_' . $key)
                    ->setParameter('t_' . $key, '%' . $term . '%')
            ;
        }

        if (null !== $limit) {
            $queryBuilder->setMaxResults($limit);
        }

        if (null !== $offset) {
            $queryBuilder->setFirstResult($offset);
        }

        return $queryBuilder
                        ->getQuery()
                        ->getResult();
    }
    
      public function findTicketByCategorie($user,CategorieEvent $cat = null,$limit = null, $offset = null) {
        $qb = $this->createQueryBuilder('p')
                ->addSelect('t','e')
                ->innerJoin('p.user', 'u')
                ->leftJoin('p.ticket', 't')
                ->leftJoin('t.event', 'e')
                ->where('u =:user')
                ->orderBy('p.date', 'DESC')
                ->setParameter('user', $user);

        if (null !== $cat) {
            $qb->andWhere('e.categorieEvent = :cat')
                    ->setParameter('cat', $cat);
        }
        
        if (null !== $limit) {
           $qb->setMaxResults($limit);
        }
        
        if (null !== $offset) {
            $qb->setFirstResult($offset);
        }

        return $qb->getQuery()->getResult();
    }


    /**
     * Removes all non-alphanumeric characters except whitespaces.
     */
    private function sanitizeSearchQuery(string $query): string {
        return trim(preg_replace('/[[:space:]]+/', ' ', $query));
    }

    /**
     * Splits the search query into terms and removes the ones which are irrelevant.
     */
    private function extractSearchTerms(string $searchQuery): array {
        $terms = array_unique(explode(' ', $searchQuery));

        return array_filter($terms, function ($term) {
            return 2 <= mb_strlen($term);
        });
    }

}

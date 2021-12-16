<?php

namespace App\Repository\Event;

use App\Entity\Event\Event;
use App\Entity\Event\CategorieEvent;
use App\Entity\Home\Tag;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Event|null find($id, $lockMode = null, $lockVersion = null)
 * @method Event|null findOneBy(array $criteria, array $orderBy = null)
 * @method Event[]    findAll()
 * @method Event[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Event::class);
    }

     public function findEventByCategorie(CategorieEvent $cat = null,$limit = null, $offset = null) {
        $qb = $this->createQueryBuilder('p')
                ->addSelect('a', 'c')
                ->innerJoin('p.author', 'a')
                ->leftJoin('p.categorieEvent', 'c')
                ->where('p.publishedAt <= :now')
                ->andWhere('p.publie = TRUE')
                ->orderBy('p.publishedAt', 'DESC')
                ->setParameter('now', new \DateTime());

        if (null !== $cat) {
            $qb->andWhere('c = :cat')
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

     public function findEventByTag( Tag $tag = null,$limit = null, $offset = null) {
        $qb = $this->createQueryBuilder('p')
                ->addSelect('a', 't')
                ->innerJoin('p.author', 'a')
                ->leftJoin('p.tags', 't')
                ->where('p.publishedAt <= :now')
                ->orderBy('p.publishedAt', 'DESC')
                ->setParameter('now', new \DateTime());

        if (null !== $tag) {
            $qb->andWhere(':tag MEMBER OF p.tags')
                    ->setParameter('tag', $tag);
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
     * @return Article[]
     */
    public function findEventBySearchQuery(string $rawQuery,$limit = null, $offset = null) {
        $query = $this->sanitizeSearchQuery($rawQuery);
        $searchTerms = $this->extractSearchTerms($query);

        if (0 === \count($searchTerms)) {
            return [];
        }

        $queryBuilder = $this->createQueryBuilder('p')
                 ->addSelect('a', 't')
                ->innerJoin('p.author', 'a')
                ->leftJoin('p.tags', 't');

        foreach ($searchTerms as $key => $term) {
            $queryBuilder
                    ->orWhere('p.titre LIKE :t_' . $key)
                    ->orWhere('t.name LIKE :t_' . $key)
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
                        ->orderBy('p.publishedAt', 'DESC')
                        ->getQuery()
                        ->getResult();
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
    
     public function incrementViews($eventId) {
        $dql = "UPDATE App\Entity\Event\Event g 
         SET g.vues = g.vues+1 
         WHERE g.id = :id";
        return $this->getEntityManager()->createQuery($dql)
                        ->setParameters(array(
                            'id' => $eventId,
                        ))
                        ->getResult();
        
      }
    
    public function incrementJaime($eventId) {
        
        //$qb = $em->createQueryBuilder() ->delete('User', 'u') ->where('u.id = :user_id') ->setParameter('user_id', 1);  

        $qb = $this->createQueryBuilder('g') 
                ->set('g.jaime', 'g.jaime+1') 
                ->where('g.id = :genre_id')
                ->setParameter('genre_id', $eventId)
                ->update(); 
  return $qb->getQuery()->getResult();
        
        
       /*  $dql = "UPDATE App\Entity\Play\GenreMusical g 
         SET g.jaime = g.jaime+1 
         WHERE g.id = :id";
        return $this->getEntityManager()->createQuery($dql)
                        ->setParameters(array(
                            'id' => $articleId,
                        ))
                        ->getResult();*/
    }
    
     public function decrementJaime($eventId) {
       $dql = "UPDATE App\Entity\Event\Event g 
         SET g.jaime = g.jaime-1 
         WHERE g.id = :id";
        return $this->getEntityManager()->createQuery($dql)
                        ->setParameters(array(
                            'id' => $eventId,
                        ))
                        ->getResult();
         
     }
    
    public function incrementJaimePas($eventId) {
        $dql = "UPDATE App\Entity\Event\Event g 
         SET g.jaimepas = g.jaimepas+1 
         WHERE g.id = :id";
        return $this->getEntityManager()->createQuery($dql)
                        ->setParameters(array(
                            'id' => $eventId,
                        ))
                        ->getResult();
        }
    
    public function decrementJaimePas($eventId) {
       $dql = "UPDATE App\Entity\Event\Event g 
         SET g.jaimepas = g.jaimepas-1 
         WHERE g.id = :id";
        return $this->getEntityManager()->createQuery($dql)
                        ->setParameters(array(
                            'id' => $eventId,
                        ))
                        ->getResult();
        }


}

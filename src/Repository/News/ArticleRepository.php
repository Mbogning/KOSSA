<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Repository\News;

use App\Entity\News\CategorieArticle;
use App\Entity\News\Article;
use App\Entity\Home\Tag;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Query;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;

/**
 * This custom Doctrine repository contains some methods which are useful when
 * querying for blog post information.
 *
 * See https://symfony.com/doc/current/doctrine/repository.html
 *
 * @author Ryan Weaver <weaverryan@gmail.com>
 * @author Javier Eguiluz <javier.eguiluz@gmail.com>
 * @author Yonel Ceruto <yonelceruto@gmail.com>
 */
class ArticleRepository extends ServiceEntityRepository {

    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, Article::class);
    }

    public function findNewsByCategorie(CategorieArticle $cat = null,$limit = null, $offset = null) {
        $qb = $this->createQueryBuilder('p')
                ->addSelect('a', 'c')
                ->innerJoin('p.author', 'a')
                ->leftJoin('p.categorie', 'c')
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

     public function findNewsByTag( Tag $tag = null,$limit = null, $offset = null) {
        $qb = $this->createQueryBuilder('p')
                ->addSelect('a', 't')
                ->innerJoin('p.author', 'a')
                ->leftJoin('p.tags', 't')
                ->where('p.publishedAt <= :now')
                ->andWhere('p.publie = TRUE')
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
    public function findNewsBySearchQuery(string $rawQuery, Article $article = null, $limit = null, $offset = null) {
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
                    ->where('p.publie = TRUE')
                    ->AndWhere('p.title LIKE :t_' . $key)
                    ->orWhere('t.name LIKE :t_' . $key)
                    ->setParameter('t_' . $key, '%' . $term . '%')
            ;
        }
        
         if (null !== $article) {
           $queryBuilder->andWhere('p != :article')
                    ->setParameter('article', $article);
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
    
     public function incrementViews($articleId) {
        $dql = "UPDATE App\Entity\News\Article g 
         SET g.vues = g.vues+1 
         WHERE g.id = :id";
        return $this->getEntityManager()->createQuery($dql)
                        ->setParameters(array(
                            'id' => $articleId,
                        ))
                        ->getResult();
        
      }
    
    public function incrementJaime($articleId) {
        
        //$qb = $em->createQueryBuilder() ->delete('User', 'u') ->where('u.id = :user_id') ->setParameter('user_id', 1);  

        $qb = $this->createQueryBuilder('g') 
                ->set('g.jaime', 'g.jaime+1') 
                ->where('g.id = :genre_id')
                ->setParameter('genre_id', $articleId)
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
    
     public function decrementJaime($articleId) {
       $dql = "UPDATE App\Entity\News\Article g 
         SET g.jaime = g.jaime-1 
         WHERE g.id = :id";
        return $this->getEntityManager()->createQuery($dql)
                        ->setParameters(array(
                            'id' => $articleId,
                        ))
                        ->getResult();
         
     }
    
    public function incrementJaimePas($articleId) {
        $dql = "UPDATE App\Entity\News\Article g 
         SET g.jaimepas = g.jaimepas+1 
         WHERE g.id = :id";
        return $this->getEntityManager()->createQuery($dql)
                        ->setParameters(array(
                            'id' => $articleId,
                        ))
                        ->getResult();
        }
    
    public function decrementJaimePas($articleId) {
       $dql = "UPDATE App\Entity\News\Article g 
         SET g.jaimepas = g.jaimepas-1 
         WHERE g.id = :id";
        return $this->getEntityManager()->createQuery($dql)
                        ->setParameters(array(
                            'id' => $articleId,
                        ))
                        ->getResult();
        }


}

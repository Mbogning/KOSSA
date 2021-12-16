<?php

namespace App\Repository\Play;

use App\Entity\Play\GenreMusical;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method GenreMusical|null find($id, $lockMode = null, $lockVersion = null)
 * @method GenreMusical|null findOneBy(array $criteria, array $orderBy = null)
 * @method GenreMusical[]    findAll()
 * @method GenreMusical[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GenreMusicalRepository extends ServiceEntityRepository {

    public function __construct(RegistryInterface $registry) {
        parent::__construct($registry, GenreMusical::class);
    }

    public function incrementViews($genreId) {
        $dql = "UPDATE App\Entity\Play\GenreMusical g 
         SET g.vues = g.vues+1 
         WHERE g.id = :id";
        return $this->getEntityManager()->createQuery($dql)
                        ->setParameters(array(
                            'id' => $genreId,
                        ))
                        ->getResult();
        
      }
    
    public function incrementJaime($genreId) {
        
        //$qb = $em->createQueryBuilder() ->delete('User', 'u') ->where('u.id = :user_id') ->setParameter('user_id', 1);  

        $qb = $this->createQueryBuilder('g') 
                ->set('g.jaime', 'g.jaime+1') 
                ->where('g.id = :genre_id')
                ->setParameter('genre_id', $genreId)
                ->update(); 
  return $qb->getQuery()->getResult();
        
        
       /*  $dql = "UPDATE App\Entity\Play\GenreMusical g 
         SET g.jaime = g.jaime+1 
         WHERE g.id = :id";
        return $this->getEntityManager()->createQuery($dql)
                        ->setParameters(array(
                            'id' => $genreId,
                        ))
                        ->getResult();*/
    }
    
     public function decrementJaime($genreId) {
       $dql = "UPDATE App\Entity\Play\GenreMusical g 
         SET g.jaime = g.jaime-1 
         WHERE g.id = :id";
        return $this->getEntityManager()->createQuery($dql)
                        ->setParameters(array(
                            'id' => $genreId,
                        ))
                        ->getResult();
         
     }
    
    public function incrementJaimePas($genreId) {
        $dql = "UPDATE App\Entity\Play\GenreMusical g 
         SET g.jaimepas = g.jaimepas+1 
         WHERE g.id = :id";
        return $this->getEntityManager()->createQuery($dql)
                        ->setParameters(array(
                            'id' => $genreId,
                        ))
                        ->getResult();
        }
    
    public function decrementJaimePas($genreId) {
       $dql = "UPDATE App\Entity\Play\GenreMusical g 
         SET g.jaimepas = g.jaimepas-1 
         WHERE g.id = :id";
        return $this->getEntityManager()->createQuery($dql)
                        ->setParameters(array(
                            'id' => $genreId,
                        ))
                        ->getResult();
        }

}

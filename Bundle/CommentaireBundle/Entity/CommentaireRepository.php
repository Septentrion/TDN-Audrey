<?php
namespace TDN\Bundle\CommentaireBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\Common\Cache\MemcacheCache as Memcache;

class CommentaireRepository extends EntityRepository {
	
    public function findAllOrderedByName() {
        return $this->getEntityManager()
            ->createQuery('SELECT p FROM AcmeStoreBundle:Product p ORDER BY p.name ASC')
            ->getResult();
    }

    public function findThreadOrdered ($thread) {
       return $this->getEntityManager()
            ->createQuery('SELECT p FROM CommentaireBundle:Commentaire p WHERE p.id_thread = ".$thread."ORDER BY p.date_publication DESC')
            ->getResult();

    }

    public function findAllThreaded () {
       $comms = $this->getEntityManager()
            ->createQuery('SELECT p FROM CommentaireBundle:Commentaire p ORDER BY p.idThread ASC, p.datePublication ASC')
            ->getResult();

        $threads = array();
        foreach ($comms as $c) {
            $threads[$c->getIdThread()][$c->getIdReponse()][] = $c;
        }
        return $threads;
    }

    public function findTest() {
        $qb = $this->createQueryBuilder('c');
        $qexpr = $qb->expr();
        $query = $qb->select('c.filAuteur')
                    ->orderBy('c.datePublication', 'DESC')
                    ->setMaxResults(10)
                    ->getQuery();

        $comms = $query->getResult();

print_r($comms);
die;
        $threads = array();
        foreach ($comms as $c) {
            $threads[$c->getIdThread()] = $c;
        }
        return $threads;
   }

    public function findByDocumentThreaded ($idDocument) {
        $cache = new Memcache;
        $_cacheKey = md5('Commentaire:SQL:'.$idDocument);

        $qb = $this->createQueryBuilder('u');
        $query = $qb->select('u')
                    ->where($qb->expr()->andX(
                        $qb-expr()->eq('filDocument', ':doc'),
                        $qb-expr()->eq('statut', ':publie')
                        ))
                    ->setParameter('doc', $idDocument)
                    ->setParameter('publie', 1)
                    ->orderBy('datePublication', 'ASC')
                    ->getQuery();

        $threads = array();
        $comms = $query->getResult();
        foreach ($comms as $c) {
            $threads[$c->getIdThread()][$c->getIdReponse()][] = $c;
        }
        return $threads;
    }

    public function searchByAuteurID ($id, $limite = NULL, $offset = NULL) {
        if ((integer)$id == 0) return array();
 
        $qb = $this->createQueryBuilder('u');
        $query = $qb->select('u')
                    ->innerJoin('u.filAuteur', 'a')
                    ->where($qb->expr()->eq('a.idNana', ':id'))
                    ->setParameter('id', $id);
        if ((integer)$limite > 0) {
            $query = $query->setMaxResults($limite);
        }
        if ((integer)$offset > 0) {
            $query = $query->setFirstResult($offset);
        }
        $query = $query->getQuery();

        $items =$query->getResult();
        // print_r($items);
        return $items;
    }

}
<?php

namespace TDN\Bundle\ConseilExpertBundle\Entity;

use Doctrine\ORM\EntityRepository;

use TDN\Bundle\DocumentBundle\Entity\DocumentRepository;

/**
 * ConseilExpertRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ConseilExpertRepository extends DocumentRepository
{
		public function findMostRead ($limite, $panel = NULL) {
		$qb = $this->createQueryBuilder('u');
		$qexpr = $qb->expr();
		$query = $qb->select('u');
		if ($panel != NULL) {
			$query = $query->innerjoin('u.rubriques', 'r')
						   ->where($qexpr->andX(
						   		$qexpr->like('u.statut', $qb->expr()->literal('CONSEIL_PUBLIE')),
						   		$qexpr->in('r.slug', ':listeRubriques')
						   	))
						   ->setParameter('listeRubriques', $panel);
		} else {
			$query = $query->where($qexpr->like('u.statut', $qb->expr()->literal('CONSEIL_PUBLIE')));
		}
		$query = $query->orderBy('u.commentThread', 'DESC')
	        		   ->setMaxResults($limite)
	        		   ->getQuery()
   	        		   ->useResultCache(true);

	     $last = $query->getResult();
	     return $last;
	}

	public function findMostLiked ($limite, $panel = NULL) {
		return parent::findMostLikedDocument($limite, 'CONSEIL_PUBLIE', $panel);
	}

	public function findMostRecent ($limite, $panel = NULL) {
		return parent::findMostRecentDocument($limite, 'CONSEIL_PUBLIE', $panel);
	}

	public function findMostRecentWithKeys ($limite, $keys) {
		if (empty($keys)) {
			return array();
		}

		return parent::findMostRecentDocumentWithKeys($limite, 'CONSEIL_PUBLIE', $keys);
	}

	public function findMostReadWithKeys ($limite, $keys) {
		if (empty($keys)) {
			return array();
		}

		return parent::findMostReadDocumentWithKeys($limite, 'CONSEIL_PUBLIE', $keys);
	}

	public function findPage ($page, $size = 50, $all = true) {
		$offset = ($page - 1) * $size;
		$qb = $this->createQueryBuilder('u');
		$query = $qb->select('u');
		if ($all !== true && is_numeric($all)) {
			$query = $query->where($qb->expr()->eq('u.lnAuteur', ':id'))
						   ->setParameter('id', $all);
		}
	    $query = $query->orderBy('u.datePublication', 'DESC')
	        		   ->setFirstResult($offset);
	    if ($size !== 0) {
	    	$query = $query->setMaxResults($size);
	    }
	    $query = $query->getQuery();
	    // ->where($qb->expr()->like('u.statut', $qb->expr()->literal('ARTICLE_PUBLIE')))

	     $last = $query->getResult();
	     return $last;
	}
}

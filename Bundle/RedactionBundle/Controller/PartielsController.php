<?php

namespace TDN\Bundle\RedactionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PartielsController extends Controller {
	
	public function articlesRecentsAction ($limite = 3, $panel = NULL) {

		$variables['typeEntite'] = 'article';
		$variables['titreEntite'] = 'Posts de la rédac';
		$variables['messageEmpty'] = 'Aucun article publié sur TDN';
		$variables['lienSommaire'] = 'Tous les articles';
		$variables['classeEntite'] = 'Article';

	    $em = $this->get('doctrine.orm.entity_manager');      
		$rep = $em->getRepository('TDN\Bundle\RedactionBundle\Entity\Article');
		$rep_sel = $em->getRepository('TDN\Bundle\RedactionBundle\Entity\SelectionShopping');
		$selectionsRecentes = $rep_sel->findMostRecent($limite, $panel);
		$articlesRecents = $rep->findMostRecent($limite, $panel);
		$variables['recents'] = array();
		for ($i = 0; $i < $limite; $i++) {
			if (empty($selectionsRecentes)) {
				$variables['recents'] = array_merge($variables['recents'], $articlesRecents);
				break;
			} elseif (empty($articlesRecents)) {
				$variables['recents'] = array_merge($variables['recents'], $selectionsRecentes);
				break;
			} else {
				if ($selectionsRecentes[0]->getDatePublication() > $articlesRecents[0]->getDatePublication()) {
					$variables['recents'][] = array_shift($selectionsRecentes);
				} else {
					$variables['recents'][] = array_shift($articlesRecents);
				}					
			}
		}
		return $this->render('TDNRedactionBundle:Partiels:articlesRecents.html.twig', $variables);
	}

	public function articlesPlusLusAction ($limite = 3, $panel = NULL, $shuffle = 30) {

	    $em = $this->get('doctrine.orm.entity_manager');      
		$rep = $em->getRepository('TDN\Bundle\RedactionBundle\Entity\Article');
		$articlesPlusLus = $rep->findMostRead($shuffle, $panel);
		shuffle($articlesPlusLus);
		$variables['articlesPlusLus'] = array_slice($articlesPlusLus, 0, $limite);

		return $this->render('TDNRedactionBundle:Partiels:articlesPlusLus.html.twig', $variables);
	}
}
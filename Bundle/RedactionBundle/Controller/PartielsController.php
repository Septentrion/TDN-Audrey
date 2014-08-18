<?php

namespace TDN\Bundle\RedactionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PartielsController extends Controller {
	
	public function articlesRecentsAction ($limite = 3, $panel = NULL) {

	    $em = $this->get('doctrine.orm.entity_manager');      
		$rep = $em->getRepository('TDN\Bundle\RedactionBundle\Entity\Article');
		$rep_sel = $em->getRepository('TDN\Bundle\RedactionBundle\Entity\SelectionShopping');
		$selectionsRecentes = $rep_sel->findMostRecent($limite, $panel);
		$articlesRecents = $rep->findMostRecent($limite, $panel);
		$variables['articlesRecents'] = array();
		for ($i = 0; $i < $limite; $i++) {
			if (empty($selectionsRecentes)) {
				$variables['articlesRecents'] = array_merge($variables['articlesRecents'], $articlesRecents);
				break;
			} elseif (empty($articlesRecents)) {
				$variables['articlesRecents'] = array_merge($variables['articlesRecents'], $selectionsRecentes);
				break;
			} else {
				if ($selectionsRecentes[0]->getDatePublication() > $articlesRecents[0]->getDatePublication()) {
					$variables['articlesRecents'][] = array_shift($selectionsRecentes);
				} else {
					$variables['articlesRecents'][] = array_shift($articlesRecents);
				}					
			}
		}
		return $this->render('TDNRedactionBundle:Partiels:articlesRecents.html.twig', $variables);
	}

	public function articlesPlusLusAction ($limite = 3, $panel = NULL, $shuffle = 30) {

	    $em = $this->get('doctrine.orm.entity_manager');      
		$rep = $em->getRepository('TDN\RBundle\edactionBundle\Entity\Article');
		$articlesPlusLus = $rep->findMostRead($shuffle, $panel);
		shuffle($articlesPlusLus);
		$variables['articlesPlusLus'] = array_slice($articlesPlusLus, 0, 3);

		return $this->render('RedactionBundle:Partiels:articlesPlusLus.html.twig', $variables);
	}
}
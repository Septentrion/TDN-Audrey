<?php

namespace TDN\Bundle\ConcoursBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use TDN\Bundle\ConseilExpertBundle\Entity\ConseilExpert;

class PartielsController extends Controller {
	
	protected $clefs = array(
		'nos-looks' => array('look', 'style', 'Relooking', 'morphologie', 'star', 'people', 'actrice', 'Blair', 'pretty little liars'),
		'accessoires-et-shoes' => array('accessoires', 'shoes', 'chaussures', 'bijoux'),
		'bonnes-affaires' => array( 'bons plans', 'soldes', 'promotions', 'réductions', 'conseils'));

	public function concoursRecentsAction ($limite = 20, $panel = NULL) {

		$variables['typeEntite'] = 'concours';
		$variables['titreEntite'] = 'Jeux-Concours';
		$variables['messageEmpty'] = 'Aucun jeu-concours publié sur TDN';
		$variables['lienSommaire'] = 'Tous les jeux-concours';
		$variables['classeEntite'] = 'Concours';

	    $em = $this->get('doctrine.orm.entity_manager');      
		$modele = $em->getRepository('TDN\Bundle\ConcoursBundle\Entity\Concours');
		if (is_array($panel)) {
			$_t = array_intersect($panel, array_keys($this->clefs));
			$isException = !empty($_t);
		} else {
			$isException = in_array($panel, array_keys($this->clefs));	
		}

		if (!empty($isException)) {
			$variables['recents'] = $modele->findMostRecentWithKeys($limite, $this->clefs[$panel[0]]);

		} else {
			$variables['recents'] = $modele->findMostRecent($limite, $panel);
		}

		return $this->render('TDNConcoursBundle:Partiels:concoursRecents.html.twig', $variables);
	}

}
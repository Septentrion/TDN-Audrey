<?php

namespace TDN\Bundle\ConseilExpertBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

use TDN\Bundle\ConseilExpertBundle\Entity\ConseilExpert;

class PartielsController extends Controller {
	
	protected $clefs = array(
		'nos-looks' => array('look', 'style', 'Relooking', 'morphologie', 'star', 'people', 'actrice', 'Blair', 'pretty little liars'),
		'accessoires-et-shoes' => array('accessoires', 'shoes', 'chaussures', 'bijoux'),
		'bonnes-affaires' => array( 'bons plans', 'soldes', 'promotions', 'réductions', 'conseils'));

	public function conseilsRecentsAction ($limite = 20, $panel = NULL) {

		$variables['activeParticipe'] = 'Pose une question à un expert';
		$variables['typeEntite'] = 'conseil-expert';
		$variables['titreEntite'] = 'Conseils d’experts';
		$variables['messageEmpty'] = 'Aucun conseil publié sur TDN';
		$variables['lienSommaire'] = 'Tous les conseils des experts';
		$variables['classeEntite'] = 'ConseilExpert';

	    $em = $this->get('doctrine.orm.entity_manager');      
		$repConseil = $em->getRepository('TDN\Bundle\ConseilExpertBundle\Entity\ConseilExpert');
		if (is_array($panel)) {
			$_t = array_intersect($panel, array_keys($this->clefs));
			$isException = !empty($_t);
		} else {
			$isException = in_array($panel, array_keys($this->clefs));	
		}

		if (!empty($isException)) {
			$variables['recents'] = $repConseil->findMostRecentWithKeys($limite, $this->clefs[$panel[0]]);

		} else {
			$variables['recents'] = $repConseil->findMostRecent($limite, $panel);
		}

		return $this->render('TDNConseilExpertBundle:Partiels:conseilsRecents.html.twig', $variables);
	}

	public function conseilsPlusLusAction ($limite = 3, $panel = NULL, $shuffle = 30) {

	    $em = $this->get('doctrine.orm.entity_manager');      
		$repConseil = $em->getRepository('TDN\Bundle\ConseilExpertBundle\Entity\ConseilExpert');
		if (is_array($panel)) {
			$_t = array_intersect($panel, array_keys($this->clefs));
			$isException = !empty($_t);
		} else {
			$isException = in_array($panel, array_keys($this->clefs));	
		}

		if (!empty($isException)) {
			$conseilsPlusLus = $repConseil->findMostRecentWithKeys($shuffle, $this->clefs[$panel[0]]);

		} else {
			$conseilsPlusLus = $repConseil->findMostRead($shuffle, $panel);
		}
		shuffle($conseilsPlusLus);
		$variables['conseilsPlusLus'] = array_slice($conseilsPlusLus, 0, 3);
		
		return $this->render('TDNConseilExpertBundle:Partiels:conseilsPlusLus.html.twig', $variables);
	}
}
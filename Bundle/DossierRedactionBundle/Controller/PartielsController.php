<?php

namespace TDN\Bundle\DossierRedactionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use TDN\Bundle\ConseilExpertBundle\Entity\ConseilExpert;

class PartielsController extends Controller {
	
	protected $clefs = array(
		'nos-looks' => array('look', 'style', 'Relooking', 'morphologie', 'star', 'people', 'actrice', 'Blair', 'pretty little liars'),
		'accessoires-et-shoes' => array('accessoires', 'shoes', 'chaussures', 'bijoux'),
		'bonnes-affaires' => array( 'bons plans', 'soldes', 'promotions', 'réductions', 'conseils'));

	public function dossiersRecentsAction ($limite = 20, $panel = NULL) {

		$variables['typeEntite'] = 'dossier';
		$variables['titreEntite'] = 'Dossiers';
		$variables['messageEmpty'] = 'Aucun dossier publié sur TDN';
		$variables['lienSommaire'] = 'Tous les dossiers de la rédaction';
		$variables['classeEntite'] = 'DossierRedaction';

	    $em = $this->get('doctrine.orm.entity_manager');      
		$repConseil = $em->getRepository('TDN\Bundle\DossierRedactionBundle\Entity\Dossier');
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

		return $this->render('TDNDossierRedactionBundle:Partiels:dossiersRecents.html.twig', $variables);
	}

}
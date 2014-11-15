<?php

namespace TDN\Bundle\NanaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PartielsController extends Controller
{
    public function showSelectionNanasAction($limite = 10)
    {
   	// Récupération de l'entity manager qui va nous permettre de gérer les entités.
	    $em = $this->get('doctrine.orm.entity_manager');      
		$repNana = $em->getRepository('TDN\Bundle\NanaBundle\Entity\Nana');

		// Récupération de la question la plus récente
		$variables['selectionNanas'] = $repNana->selectionNanas($limite);
		$variables['nanaDeLaSemaine'] = $repNana->nanaDeLaSemaine();
		$variables['cardInscrits'] = $repNana->count('all');

        return $this->render('TDNNanaBundle:Partiels:footerWidget.html.twig', $variables);
    }
}

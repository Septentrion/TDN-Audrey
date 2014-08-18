<?php

namespace TDN\Bundle\CauseuseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PartielsController extends Controller
{
    public function questionsRecentesAction($limite)
    {
    	// Récupération de l'entity manager qui va nous permettre de gérer les entités.
	    $em = $this->get('doctrine.orm.entity_manager');      

	    $variables = array();
		// Récupération de la question la plus récente
		$repCauseuse = $em->getRepository('TDN\Bundle\CauseuseBundle\Entity\Question');
		$variables['questionsRecentes'] = $repCauseuse->findMostRecent($limite);

        return $this->render('TDNCauseuseBundle:Partiels:questionsRecentes.html.twig', $variables);
    }
}

<?php

namespace TDN\Bundle\CauseuseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PartielsController extends Controller
{

    public function digestHomeAction ($limite) {

		$variables['typeEntite'] = 'question';
		$variables['titreEntite'] = 'Questions de nanas (sans tabou)';
		$variables['messageEmpty'] = 'Aucune question publié sur TDN';
		$variables['lienSommaire'] = 'Toutes les questions des nanas';
		$variables['classeEntite'] = 'Causeuse';
    	$variables['recents'] = $this->_getQuestionsRecentes($limite);
    	$variables['aimees'] = $this->_getQuestionsAimees($limite);

        return $this->render('TDNCauseuseBundle:Partiels:digestHome.html.twig', $variables);
    }

    public function questionsRecentesAction($limite) {

    	$variables['questionsRecentes'] = $this->_getQuestionsRecentes($limite);
        return $this->render('TDNCauseuseBundle:Partiels:questionsRecentes.html.twig', $variables);
    }

    private function _getQuestionsRecentes ($limite) {

    	// Récupération de l'entity manager qui va nous permettre de gérer les entités.
	    $em = $this->get('doctrine.orm.entity_manager');      

	    $variables = array();
		// Récupération de la question la plus récente
		$repCauseuse = $em->getRepository('TDN\Bundle\CauseuseBundle\Entity\Question');
		$variables = $repCauseuse->findMostRecent($limite);

		return $variables;
    }

    public function questionsPlusAimeesAction($limite) {

		$variables['typeEntite'] = 'question';
		$variables['titreEntite'] = 'Questions de nanas (sans tabou)';
		$variables['messageEmpty'] = 'Aucune question publié sur TDN';
		$variables['lienSommaire'] = 'Toutes les questions des nanas';
		$variables['classeEntite'] = 'Causeuse';
		$variables['recents'] = $this->_getQuestionsAimees($limite);

        return $this->render('TDNCauseuseBundle:Partiels:questionsPlusAimees.html.twig', $variables);
    }

    private function _getQuestionsAimees($limite) {

     	// Récupération de l'entity manager qui va nous permettre de gérer les entités.
	    $em = $this->get('doctrine.orm.entity_manager');      

		// Récupération de la question la plus récente
		$repCauseuse = $em->getRepository('TDN\Bundle\CauseuseBundle\Entity\Question');
		$variables = $repCauseuse->findMostLiked($limite);

		return $variables;
    }
}

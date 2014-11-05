<?php

namespace TDN\Bundle\DocumentBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use TDN\Bundle\DocumentBundle\Entity\Slider;
use TDN\Bundle\DocumentBundle\Form\Type\SlideInspecteurType;


class SliderController extends Controller
{
    
	public function showAction ($limite = 6)
	{
	    // Récupération de l'entity manager qui va nous permettre de gérer les entités.
	    $em = $this->get('doctrine.orm.entity_manager');      

		// Instanciation du Repository
		$repository = $em->getRepository('TDN\Bundle\DocumentBundle\Entity\Slider');
		$variables['slider'] = $repository->findSelection($limite);
		$err = shuffle($variables['slider']);
		foreach ($variables['slider'] as $contenu) {
			$source = $contenu->getLnSource();
			list($route, $rubrique, $params) = $source->getURLElements();
			$variables['routes'][] = $this->generateURL($route, $params);
		}

        return $this->render('TDNDocumentBundle:Slider:sliderPlayer.html.twig', $variables);
	}
}
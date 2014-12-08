<?php

namespace TDN\Bundle\ProduitBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PartielsController extends Controller
{
    public function panoramaCoupsDeCoeurAction($limite = 8)
    {
	    $em = $this->get('doctrine.orm.entity_manager');      
		$rep = $em->getRepository('TDN\Bundle\ProduitBundle\Entity\Produit');
		// $variables['coupsDeCoeur'] = $rep->findBy(array('favori' => '1'), array('datePublication' => 'DESC'), $limite);
		$variables['coupsDeCoeur'] = $rep->selectionPanorama($limite);

		return $this->render('TDNProduitBundle:Partiels:panoramaCoupsDeCoeur.html.twig', $variables);
    }
}

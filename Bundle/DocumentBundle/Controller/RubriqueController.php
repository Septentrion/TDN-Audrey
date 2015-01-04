<?php

namespace TDN\Bundle\DocumentBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use TDN\Bundle\DocumentBundle\Entity\DocumentRubrique;
use TDN\Bundle\DocumentBundle\Form\Type\modifyRubriqueType;
use TDN\Bundle\DocumentBundle\Form\Type\defineRubriqueType;

class RubriqueController extends Controller
{
	private $_princeps = array('a-la-une' => NULL, 'beaute' => NULL, 'geekette' => NULL, 'deco' => NULL, 'mode' => NULL, 'bien-etre' => NULL, 'sexo-psycho' => NULL, 'recettes' => NULL);

    
    public function indexAction() {
	
		$variables['Liste'] = array();
		$variables['colonnesList'] = array('Rubrique', 'Sponsor');
		$variables['actionsRoutes'] = array();
			// array('Supprimer' => array('NanaRole_supprimerCredit', "'role_id': role.role, 'nana_id': personne.idNana"));

		$em = $this->get('doctrine.orm.entity_manager');
		$variables['Liste'] = $em->getRepository('TDN\Bundle\DocumentBundle\Entity\DocumentRubrique')->findTop();

        return $this->render('TDNDocumentBundle:Rubrique:dashboard.html.twig', $variables);
    }
    
    public function creerAction() {

		$em = $this->get('doctrine.orm.entity_manager');
		$request = $this->get('request');

		$variables['rubrique'] = 'tdn';
		// Affichage de la page

		// Instanciation du formulaire
		$rubrique = new DocumentRubrique;
		$form = $this->createForm(new defineRubriqueType, $rubrique);

		// Traitement du formulaire
		if ($request->getMethod() == "POST") {
			$form->bind($request);
			if ($form->isValid()) {

				$rubrique->setDatePublication(new \DateTime);
				$rubrique->setDateModification(new \DateTime);
				if ($rubrique->getRubriqueParente() != NULL) {
					$rubrique->setParent($rubrique->getRubriqueParente()->getIdRubrique());
				}

				$em->persist($rubrique);
				$em->flush();
			}

	        return $this->redirect($this->generateURL('DocumentRubrique_Index'));
		}

		// Affichage
		$variables['form'] = $form->createView();
		$variables['h1'] = "Nouvelle rubrique";
        return $this->render('TDNDocumentBundle:Rubrique:creerRubrique.html.twig', $variables);
    }
    
    public function modifierAction($id) {

		$variables['rubrique'] = 'tdn';

		$request = $this->get('request');
		$em = $this->get('doctrine.orm.entity_manager');
		$rubrique = $em->getRepository('TDN\Bundle\DocumentBundle\Entity\DocumentRubrique')->find($id);

		// Instanciation du formulaire
		$form = $this->createForm(new defineRubriqueType, $rubrique);

		if ($request->getMethod() == "POST") {
			$form->bind($request);

			$rubrique->setDateModification(new \DateTime);

			$em = $this->get('doctrine.orm.entity_manager');
			$em->persist($rubrique);
			$em->flush();
	
	        return $this->redirect($this->generateURL('DocumentRubrique_Index'));
		}

		$variables['form'] = $form->createView();
		$variables['h1'] = "Modification de la rubrique : ".$rubrique->getTitre();
		$variables['id'] = $id;

        return $this->render('TDNDocumentBundle:Rubrique:majRubrique.html.twig', $variables);
    }

    public function saveAction($id) {
		
		$em = $this->get('doctrine.orm.entity_manager');
		$request = $this->get('request');

		$rubrique = $em->getRepository('TDN\Bundle\DocumentBundle\Entity\DocumentRubrique')->find($id);

		if ($request->getMethod() == "POST") {
			$form = $this->createForm(new modifyRubriqueType, $rubrique);
			if (true) {
				$form->bind($request);

				$rubrique->setDateModification(new \DateTime);

				$em = $this->get('doctrine.orm.entity_manager');
				$em->persist($rubrique);
				$em->flush();
			}
		}

        return $this->redirect($this->generateURL('DocumentBundle_homepage'));
    }

    public function menuRubriquesAction () {
		$em = $this->get('doctrine.orm.entity_manager');
		$rep = $em->getRepository('TDN\Bundle\DocumentBundle\Entity\DocumentRubrique');

		$rubriques = $rep->findAll();
		$_vars['princeps'] = $this->_princeps;
		$clefsPrinceps = array_keys($this->_princeps);
		foreach($rubriques as $r) {
			$s = $r->getSlug();
			if (in_array($s, $clefsPrinceps) && ($r->getStatut() != 0)) {
				$_vars['princeps'][$s] = $r;
			} elseif (is_object($r->getRubriqueParente()) && ($r->getStatut() == 1)) {
				$p = $r->getRubriqueParente()->getSlug();
				$_vars['navigation'][$p][] = $r;
			} else {}
		}

        return $this->render('TDNCoreBundle:Partiels:menuRubriques.html.twig', $_vars);

    }

}

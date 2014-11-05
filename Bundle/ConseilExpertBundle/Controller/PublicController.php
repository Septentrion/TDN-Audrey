<?php

namespace TDN\Bundle\ConseilExpertBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Response;

use TDN\Bundle\ConseilExpertBundle\Entity\ConseilExpert;
use TDN\Bundle\ConseilExpertBundle\Form\Type\ConseilExpertSoumissionType;

use TDN\Bundle\DocumentBundle\Controller\PublicController as MainPublicController;
use TDN\Bundle\DocumentBundle\Form\Type\selecteurRubriquesType;
use TDN\Bundle\DocumentBundle\Entity\DocumentRubrique;
use TDN\Bundle\DocumentBundle\Form\Model\Thematique;
use TDN\Bundle\DocumentBundle\Form\Type\ThematiquePrincipaleType as ThematiqueType;

use TDN\Bundle\NanaBundle\Entity\Nana;

use TDN\Bundle\ImageBundle\Entity\Image;

class PublicController extends MainPublicController {
	
	public function conseilDemandeAction () {

		$request = $this->get('request');
	    $em = $this->get('doctrine.orm.entity_manager');      

	    // Utilisateur connecté
		// $usr= $this->get('security.context')->getToken()->getUser();
	    $rep_nanas = $em->getRepository('TDN\Bundle\NanaBundle\Entity\Nana');
		$usr = $rep_nanas->find(1);

		// Instanciation du formulaire
		$form = $this->createForm(new ConseilExpertSoumissionType, new ConseilExpert);
		// Menu déroulant pour la rubrique
		$formRubrique = $this->createForm(new ThematiqueType, new Thematique);

		$variables['nana'] = $usr;
		$variables['titreFormulaire'] = 'Questionne les nanas';
		$variables['rubrique'] = 'tdn';
		$variables['form'] = $form->createView();
		$variables['formRubrique'] = $formRubrique->createView();

		// Affichage de la page
		return $this->render('TDNConseilExpertBundle:Pages:conseilExpertDemandeForm.html.twig', $variables);
	}

	public function conseilDemandeProcessAction () {

		$variables['rubrique'] = 'tdn';

		$request = $this->get('request');
	    // Récupération de l'entity manager qui va nous permettre de gérer les entités.
	    $em = $this->get('doctrine.orm.entity_manager');      

		// Instanciation du formulaire
		$_TDNDocument = new ConseilExpert;
		$form = $this->createForm(new ConseilExpertSoumissionType, $_TDNDocument);
		// Menu déroulant pour la rubrique
		$_rubrique =  new Thematique;
		$formRubrique = $this->createForm(new ThematiqueType, $_rubrique);

		$form->bind($request);
		if (!$form->isValid()) {
			$this->get('session')->getFlashBag()->add('error', 'Il y a une erreur dans ta demande');
			return $this->render('TDNConseilExpertBundle:Pages:conseilExpertDemandeForm.html.twig', $variables);
		}
		$usr= $this->get('security.context')->getToken()->getUser();
	    $rep_nanas = $em->getRepository('TDN\Bundle\NanaBundle\Entity\Nana');
		$usr = $rep_nanas->find(1);
		$_TDNDocument->init();
		$_TDNDocument->setLnAuteur($usr);

		// Traitement de l'image envoyée							
		$imageNana = $_TDNDocument->getLnImage();
		if ($imageNana instanceof Image) {
			$imageProcessor = $this->get('tdn.image_processor');
			$formats = array('square' => 'default','paysage' => 'slider');
			$imageProcessor->make($imageNana, $usr, 'docs', $formats);
		}	

		// Gestion du rubriquage des contenus
		$formRubrique->bindRequest($request);
		$_TDNDocument->addRubrique($_rubrique->getRubrique());

		$em = $this->get('doctrine.orm.entity_manager');
		// $em->persist($_TDNDocument);
		// $em->flush();

		// Notification
		// $higgins = $this->get('tdn.notifieur');
		// $err = $higgins->notification_admin($corps, 'TDNConseilExpertBundle:Mail:demandeConseil.html.twig');
		$admins = $this->container->getParameter('admin_notifications');
		$expediteurs = $this->container->getParameter('mail_expediteur');				
		$message = \Swift_Message::newInstance();
		$corps['expediteur'] = "Administrateur";
		$corps['role'] = "Système";
		$corps['destinataire'] = "Justine";
		$corps['dateEnvoi'] = date(' d m Y - H:i:s');
		$corps['pseudo'] = $usr->getUsername();
		$corps['question'] = $_TDNDocument->getQuestion();

		$message->setSubject('[TDN] Demande de conseil')
				->setContentType('text/html')
    			->setFrom($expediteurs['admin'])
        			->setBody(
        			$this->renderView('TDNConseilExpertBundle:Mail:demandeConseil.html.twig', $corps),
        			'text/html'
        		);
		foreach($admins['redaction'] as $destinataire) {
			$message->addTo($destinataire);
		}
	    $this->get('mailer')->send($message);

		$this->get('session')->getFlashBag()->add('success', 'Merci. Ta question va être envoyée à un de nos experts qui te répondra au plus vite');
		return $this->redirect($this->generateURL('Core_home'));
	}

	public function conseilAction ($rubrique, $id, $slug = NULL) {
	/* Tableau qui va stocker toutes les données à remplacer dans le template twig */
	    $variables = array();  

	    // Récupération de l'entity manager qui va nous permettre de gérer les entités.
	    $em = $this->get('doctrine.orm.entity_manager');      
		$rep_conseils = $em->getRepository('TDN\Bundle\ConseilExpertBundle\Entity\ConseilExpert');
		$variables['TDNDocument'] = $rep_conseils->find($id);

		if ($variables['TDNDocument'] instanceof ConseilExpert) {
			$rubriques = $variables['TDNDocument']->getRubriques();
			$_thematique = $variables['TDNDocument']->getLnThematique();
			if ($_thematique instanceof DocumentRubrique) {
				$variables['rubriquePrincipale'] = $_thematique;
				$variables['rubrique'] = $_thematique->getSuperSlug();
				$variables['titreRubrique'] = $_thematique->getTitre();
			} else {
				$variables['rubriquePrincipale'] = $rubriques[0];
				$variables['rubrique'] = $rubriques[0]->getSuperSlug();
				$variables['titreRubrique'] = $rubriques[0]->getTitre();
			}

			if ($_thematique === $rubriques[0]) {
				$variables['istheme'] = false;
			} else {
				$variables['istheme'] = true;
			}

			$variables['auteur'] = $variables['TDNDocument']->getLnAuteur();
			$variables['expert'] = $variables['TDNDocument']->getLnExpert();

			$variables['dateDemande'] = "10/10/2012";
			
			$variables['TDNDocument']->updateHits();
			$em->flush();
				
			$variables['canonical'] = $this->generateURL('ConseilExpert_page', 
				array('id' => $variables['TDNDocument']->getIdDocument(),
					  'slug' => $variables['TDNDocument']->getSlug(),
					  'rubrique' => $variables['rubrique'],
					  'theme' => $variables['rubriquePrincipale']->getSlug())
				);
			$variables['meta_description'] = strip_tags($variables['TDNDocument']->getAbstract());
			
			// Documents proches (pour aller plus loin)
		    $rep_tags = $em->getRepository('TDN\Bundle\DocumentBundle\Entity\Tag');
		    $sims = $rep_tags->findSimilars($id);

		    $rep_docs = $em->getRepository('TDN\Bundle\DocumentBundle\Entity\Document');
		    $variables['similaires'] = array();
		    foreach($sims as $s) {
		    	$variables['similaires'][] = $rep_docs->find($s['id']);
		    }

		    $variables['paths'] = array(
		    	'Article' => 'RedactionBundle_article',
		    	'ConseilExpert' => 'ConseilExpert_conseil',
		    	'Question' => 'CauseuseBundle_conversation',
		    	'Video' => 'VideoBundle_video',
		    	'Dossier' => 'DossierRedaction_dossier'
		    	);

			// Affichage de la page
			return $this->render('TDNConseilExpertBundle:Pages:conseil.html.twig', $variables);			
		} else {
	        $response = $this->render('TDNCoreBundle:Errors:410-gone.html.twig', array('rubrique' => 'tdn'));
            return new Response($response, 410);
		}
	}

	public function sommaireAction ($theme = '', $rubrique = '') {

        $request = $this->get('request');

		$variables = $this->makeSommaire($rubrique, 'TDN\Bundle\ConseilExpertBundle\Entity\ConseilExpert', 'CONSEIL_PUBLIE');

		$channel = $request->query->get('channel');
		if ($channel === 'ajax') {
			$response = new Response($this->renderView('TDNConseilExpertBundle:Partiels:conseilsListe.html.twig', $variables));
	        $response->headers->set('Content-Type', 'text/html');
	        $response->headers->set('Accept-Charset', 'utf-8');
	        return $response;

		} else {
			// Affichage de la page
	        $variables['titreSommaire'] = 'Conseils d’experts';
			$variables['routeSommaire'] = 'ConseilExpert_sommaire';
			return $this->render('TDNConseilExpertBundle:Pages:conseilSommaire.html.twig', $variables);
		}
	}

	public function filPersoAction ($id) {

		$variables['rubrique'] = 'tdn';
	    $em = $this->get('doctrine.orm.entity_manager');      
		$whoami= $this->get('security.context')->getToken()->getUser();
		// Récupération des demandes de conseil
		$criteres = array('lnAuteur' => $id);
		if (!(($whoami instanceof Nana) && ($id == $whoami->getIdNana()))) {
			$criteres['statut'] = 'CONSEIL_PUBLIE';
			$rep = $em->getRepository('TDN\Bundle\NanaBundle\Entity\Nana');
			$variables['nana'] = $rep->find($id);
		} else {
			$variables['nana'] = $whoami;
		}
		$rep = $em->getRepository('TDN\Bundle\ConseilExpertBundle\Entity\ConseilExpert');
		$variables['conseilsList'] = $rep->findBy($criteres, array('dateSoumission' => 'DESC'));

		// Préparation de la page
		$variables['labelStatut'] = array(
			'CONSEIL_ENREGISTRE' => array('Enregistré', 'enregistre'), 
			'CONSEIL_TRANSMIS' => array('Transmis à l’expert', 'transmis'), 
			'CONSEIL_REPONDU' => array('En relecture', 'relecture'), 
			'CONSEIL_PUBLIE' => array('Publié', 'publie'),
			'CONSEIL_ECARTE' => array('Non accepté', 'ecarte')
		);

		// Affichage de la page
		return $this->render('ConseilExpertBundle:Bloc:filPerso.html.twig', $variables);

	}
}

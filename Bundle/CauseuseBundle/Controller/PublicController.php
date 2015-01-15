<?php

namespace TDN\Bundle\CauseuseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

use TDN\Bundle\CauseuseBundle\Entity\Question;
use TDN\Bundle\CauseuseBundle\Form\Type\CauseuseSoumissionType;
use TDN\Bundle\CauseuseBundle\Entity\Reponse;
use TDN\Bundle\CauseuseBundle\Form\Type\CauseuseReponseType;

use TDN\Bundle\DocumentBundle\Controller\PublicController as MainPublicController;
use TDN\Bundle\DocumentBundle\Entity\DocumentRubrique;
use TDN\Bundle\DocumentBundle\Form\Model\Thematique;
use TDN\Bundle\DocumentBundle\Form\Type\ThematiquePrincipaleType;

use TDN\Bundle\NanaBundle\Entity\Nana;
use TDN\Bundle\ImageBundle\Entity\Image;
use TDN\Bundle\CoreBundle\Entity\Journal;


class PublicController extends MainPublicController {
	
	public function questionAction ($theme, $rubrique, $slug, $id) {

		$request = $this->get('request');
		$usr= $this->get('security.context')->getToken()->getUser();

		/* Tableau qui va stocker toutes les données à remplacer dans le template twig */
	    $variables = array();  

	    // Récupération de l'entity manager qui va nous permettre de gérer les entités.
	    $em = $this->get('doctrine.orm.entity_manager');
	    $urlManager = $this->get('tdn.document.url');

		// Instanciation du Repository
		$rep = $em->getRepository('TDN\Bundle\CauseuseBundle\Entity\Question');
		$question = $variables['TDNDocument'] = $rep->find($id);

		// Instanciation du formulaire de réponse
		$form = $this->createForm(new CauseuseReponseType, new Reponse);
		$variables['formReponse'] = $form->createView();

		$reponses = $question->getFilReponses();
		$variables['nbReponses'] = (empty($reponses)) ? 0 : $reponses->count();
		$variables['totalVotes'] = 0;
		foreach ($reponses as $r) {
			$variables['totalVotes'] += $r->getLikes();
		}

		$auteur = $question->getLnAuteur();
		$dateNaissance = $auteur->getDateNaissance();
		$now = new \DateTime;
		$variables['ageAuteur'] = $dateNaissance->diff($now)->format('%y');

		$_rubriques = $question->getRubriques();
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
		$variables['reponses'] = NULL;
		// $variables['canonical'] = $urlManager->canonicalURL($question);
		$variables['meta_description'] = strip_tags($question->getAbstract());

		// Documents proches (pour aller plus loin)
	    $rep_tags = $em->getRepository('TDN\Bundle\DocumentBundle\Entity\Tag');
	    $statement = $this->get('database_connection');	    
	    $sims = $rep_tags->findSimilars($id);

		$variables['canonical'] = $this->generateURL('Question_page', 
			array('id' => $variables['TDNDocument']->getIdDocument(),
				  'slug' => $variables['TDNDocument']->getSlug(),
				  'rubrique' => $variables['rubrique'],
				  'theme' => $variables['rubriquePrincipale']->getSlug())
			);

	    $rep_docs = $em->getRepository('TDN\Bundle\DocumentBundle\Entity\Document');
	    $variables['similaires'] = array();
	    foreach($sims as $s) {
	    	$variables['similaires'][] = $rep_docs->find($s['id']);
	    }

	    $variables['paths'] = array(
	    	'Article' => 'RedactionBundle_article',
	    	'ConseilExpert' => 'ConseilExpert_conseil',
	    	'Question' => 'Causeuse_conversation',
	    	'Video' => 'VideoBundle_video',
	    	'Dossier' => 'DossierRedaction_dossier'
	    	);

		// Affichage de la page
		return $this->render('TDNCauseuseBundle:Pages:questionNanas.html.twig', $variables);
	}


	public function sommaireAction ($rubrique = NULL)
	{
        $request = $this->get('request');

		$variables = $this->makeSommaire($rubrique, 'TDN\Bundle\CauseuseBundle\Entity\Question', 'QUESTION_PUBLIEE');

		$channel = $request->query->get('channel');
		if ($channel === 'ajax') {
			$response = new Response($this->renderView('TDNCauseuseBundle:Partiels:questionsNanasListe.html.twig', $variables));
	        $response->headers->set('Content-Type', 'text/html');
	        $response->headers->set('Accept-Charset', 'utf-8');
	        return $response;

		} else {
			// Affichage de la page
	        $variables['titreSommaire'] = 'Conseils d’experts';
			$variables['routeSommaire'] = 'Causeuse_sommaire';
			for ($i = 0 ; $i < 2; $i++) if (!empty($variables['listeContenus'])) {
				$variables['featuredContenus'][] = array_shift($variables['listeContenus']);
			}
			return $this->render('TDNCauseuseBundle:Pages:questionNanasSommaire.html.twig', $variables);
		}
	}


	public function questionDemandeAction () {

		$request = $this->get('request');
		$usr= $this->get('security.context')->getToken()->getUser();
	    $em = $this->get('doctrine.orm.entity_manager');      
		$URLmanager = $this->get('tdn.document.url');

	    $rep_nanas = $em->getRepository('TDN\Bundle\NanaBundle\Entity\Nana');
		$usr = $rep_nanas->find(1);

	    if (!($usr instanceof Nana)) {
			$this->get('session')->getFlashBag()->add('access', 'Connecte-toi pour poser une question');
	 		return $this->redirect($URLmanager->refererURL($request->headers->get('referer')));
	    }
	    // Gestion des utilisateurs sur liste noire
    	if ($usr->getBlackList()) {
			$this->get('session')->getFlashBag()->add('access', 'Contacte TDN pour pouvoir publier à nouveau');
			return $this->redirect($URLmanager->refererURL($request->headers->get('referer')));
    	}

		$variables['rubrique'] = 'tdn';
		$variables['id'] = $usr->getidNana();
		$variables['pseudo'] = $usr->getUsername();
		$roles = $usr->getRoles();
		$rp = $roles[0];
		$variables['statut'] = $rp->getName();

		// Instanciation du formulaire
		$_TDNDocument = new Question;
		$form = $this->createForm(new CauseuseSoumissionType, $_TDNDocument);

		// Menu déroulant pour la rubrique
		$_rubrique =  new Thematique;
		$formRubrique = $this->createForm(new ThematiquePrincipaleType, $_rubrique);

		if ($request->getMethod() == "POST") {
			$form->bind($request);
			$now = new \DateTime;
	
			$_TDNDocument->setLnAuteur($usr);
			$_TDNDocument->setTitre("");
			$_TDNDocument->setSlug("");
			$_TDNDocument->setLikes(0);
			$_TDNDocument->setHits(0);
			$_TDNDocument->setStatut('QUESTION_POSEE');
			$_TDNDocument->setVersion("1.0");
			$_TDNDocument->setTags("");
			$_TDNDocument->setDateSoumission(new \DateTime);
			$_TDNDocument->setDatePublication(new \DateTime);
			$_TDNDocument->setDateModification(new \DateTime);
			// Initianlisation des commentaires
			$_TDNDocument->setCommentThread(new \Doctrine\Common\Collections\ArrayCollection());
			// Pas de promotion automatique en page d'accueil (Slider)
			$_TDNDocument->setLnPromu(NULL);
			// Association des rubriques
			$formRubrique->bindRequest($request);
			$_TDNDocument->addRubrique($_rubrique->getRubrique());

			// Modification de l'illustration de la question
			$imageNana = $_TDNDocument->getLnIllustration();
			if ($_TDNDocument->getLnIllustration() instanceof Image) {
				$legende = $imageNana->getTitre();
				if (empty($legende)) $imageNana->setTitre('image postée par '.$usr->getUsername());
				$dossier = '/'.$this->container->getParameter('tdn_media').$now->format('Y').'/'.$now->format('m').'/n_/'.$usr->getidNana();
				$imageNana->init($dossier, $usr);
				$em->persist($imageNana);
			}	

			$em->persist($_TDNDocument);
			$em->flush();

			// Post-traitement de l'image
			if ($_TDNDocument->getLnIllustration() instanceof Image) {
				$imageProcessor = $this->get('tdn.image_processor');
				$rep_nana = $em->getRepository('TDN\Bundle\NanaBundle\Entity\Nana');
				$fichierImage = $_TDNDocument->getLnIllustration()->getFichier();
	            $source = $this->container->getParameter('media_root').$dossier.$fichierImage;
	            $err = $imageProcessor->square($source, 300, 'sqr_');
	            $err = $imageProcessor->downScale($source, 700, 'height');
			}


			// Envoir d'un message de notification à la rédaction en chef
			$message = \Swift_Message::newInstance();
			$corps['expediteur'] = "Administrateur";
			$corps['role'] = "Système";
			$corps['destinataire'] = "Justine";
			$corps['dateEnvoi'] = date(' d m Y - H:i:s');
			$corps['pseudo'] = $usr->getUsername();
			$corps['question'] = $_TDNDocument->getQuestion();

			$message->setSubject('[TDN] Soumission de question aux nanas')
					->setContentType('text/html')
        			->setFrom('postmaster@trucdenana.com')
        			->addTo('michel.cadennes@sens-commun.fr')
        			->addTo('justine@trucdenana.com')
        			->setBody(
            			$this->renderView('CauseuseBundle:Mail:soumissionQuestion.html.twig', $corps),
            			'text/html'
            		);
		    $this->get('mailer')->send($message);

			$this->get('session')->getFlashBag()->add('success', 'Merci. Ta question va être examinée par la rédaction');
			return $this->redirect($URLmanager->refererURL($request->headers->get('referer')));
		}

		$variables['nana'] = $usr;
		$variables['titreFormulaire'] = 'Questionne les nanas';
		// Affichage de la page
		$variables['form'] = $form->createView();
		$variables['formRubrique'] = $formRubrique->createView();
		return $this->render('TDNCauseuseBundle:Pages:questionDemandeForm.html.twig', $variables);
	}

	public function reponsePosterAction () {

		$request = $this->get('request');
		$URLmanager = $this->get('tdn.document.url');

		$variables['rubrique'] = 'tdn';
		$usr= $this->get('security.context')->getToken()->getUser();

	    // Gestion des utilisateurs sur liste noire
    	if ($usr->getBlackList()) {
			$this->get('session')->getFlashBag()->add('fail', 'Tu n’es actuellement plus autorisé(e) à publier sur le site. Contacte TDN pour pouvoir publier à nouveau');
			return $this->redirect($URLmanager->refererURL($request->headers->get('referer')));
    	}

		// Instanciation du formulaire
		$_TDNReponse = new Reponse;
		$form = $this->createForm(new CauseuseReponseType, $_TDNReponse);
		$variables['form'] = $form->createView();

	    // Récupération de l'entity manager qui va nous permettre de gérer les entités.
	    $em = $this->get('doctrine.orm.entity_manager');      
		// Instanciation du Repository
		$rep = $em->getRepository('TDN\Bundle\CauseuseBundle\Entity\Question');
		$idQuestion = $request->get('idQuestion');
		$_TDNDocument = $rep->find($idQuestion);

		if ($request->getMethod() == "POST") {

			$form->bind($request);
			$_TDNReponse->setTitre("");
			$_TDNReponse->setSlug("");
			$_TDNReponse->setLikes(0);
			$_TDNReponse->setHits(0);
			$_TDNReponse->setStatut('REPONSE_PUBLIEE');
			$_TDNReponse->setVersion("1.0");
			$_TDNReponse->setTags("");
			$_TDNReponse->setDatePublication(new \DateTime);
			$_TDNReponse->setDateModification(new \DateTime);
			$_TDNReponse->setLnConversation($_TDNDocument);

			$shortener = $this->get('tdn.core.urlshortener');
			$_reponse = $_TDNReponse->getReponse();
			$err = preg_match_all('#(http://[^\s]+)#', $_reponse, $links, PREG_OFFSET_CAPTURE);
			foreach($links[1] as $l) {
				$source = $l[0];
				$debut = $source[1];
				$fin = $debut + strlen($source);
				$short = $shortener->url_shortener($source);
				$url = "<em>(<a target='_blank' href='$short'>Voir cette page</a>)</em>";
				$_reponse = str_replace($source, $url, $_reponse);
			}
			$_TDNReponse->setReponse($_reponse);

			$_TDNReponse->setLnAuteur($usr);

			// Initianlisation des commentaires
			$_TDNReponse->setCommentThread(new \Doctrine\Common\Collections\ArrayCollection());
			// Pas de promotion automatique en page d'accueil (Slider)
			$_TDNReponse->setLnPromu(NULL);
				
			$imageNana = $_TDNReponse->getLnIllustration();

			if ($imageNana instanceof Image) {
				$now = new \DateTime;
				$dossier = '/public/'.$now->format('Y').'/'.$now->format('m').'/n_/'.$usr->getIdNana().'/';
				$imageNana->init($dossier);
				// Post-traitement de l'image
				$imageProcessor = $this->get('tdn.image_processor');
				$rep_nana = $em->getRepository('TDN\Bundle\NanaBundle\Entity\Nana');
				$fichierImage = $_TDNReponse->getLnIllustration()->getFichier();
	            $source = $this->container->getParameter('media_root').$dossier.$fichierImage;
	            $err = $imageProcessor->square($source, 300, 'sqr_');
	            $err = $imageProcessor->downScale($source, 700, 'height');
			}

			// // Modification de l'illustration de la question
			// Désactivé : A priori aucune raison valable de faire ça
			// $now = new \DateTime;
			// $hasNewIllustration = false;
			// $dossierIllustration = '/public/'.$now->format('Y').'/'.$now->format('m').'/n_/'.$usr->getidNana().'/';
			// $imageNana = $_TDNDocument->getLnIllustration();
			// if ($imageNana instanceof Image) {
			// 	$legende = $imageNana->getTitre();
			// 	if (empty($legende)) $imageNana->setTitre('image postée par '.$usr->getUsername());
			// 	$imageNana->init($dossierIllustration, $usr);
			// 	$em->persist($imageNana);
			// 	$hasNewIllustration = true;
			// }	

			$em = $this->get('doctrine.orm.entity_manager');
			$em->persist($_TDNReponse);

			// Notification
			$admins = $this->container->getParameter('admin_notifications');
			$expediteurs = $this->container->getParameter('mail_expediteur');				
			$message = \Swift_Message::newInstance();
			$corps['expediteur'] = "Athanase";
			$corps['role'] = "Modérateur";
			$corps['destinataire'] = $_TDNDocument->getLnAuteur()->getUsername();
			$corps['dateEnvoi'] = date(' d m Y - H:i:s');
			$corps['pseudo'] = $usr->getUsername();
			$corps['question'] = $_TDNDocument->getTitre();
			$corps['reponse'] = $_TDNReponse->getReponse();
			$params['slug'] = $_TDNDocument->getSlug();
			$params['id'] = $_TDNDocument->getIdDocument();
			$_r = $_TDNDocument->getRubriques();
			$params['rubrique'] = $_r[0]->getSlug();
 			$corps['url'] = 'http://www.trucsdenana.com'.$this->generateURL('Question_page', $params);

			$message->setSubject('[TDN] '.$usr->getUsername().' a répondu à une de tes questions aux nanas')
					->setContentType('text/html')
        			->setFrom($expediteurs['redaction'])
        			->addTo($_TDNDocument->getLnAuteur()->getEmail())
         			->setBody(
            			$this->renderView('CauseuseBundle:Mail:notificationReponse.html.twig', $corps),
            			'text/html'
            		);
			foreach($admins['redaction'] as $destinataire) {
				$message->addBcc($destinataire);
			}
		    $this->get('mailer')->send($message);

			// Signaler dans le journal
			$entree = new Journal;
			if ($usr instanceof Nana) $entree->setLnActeur($usr);
			$entree->setAction('REPONSE');
			list($route, $rubrique, $params) = $_TDNDocument->getURLElements();
			$entree->setURL($this->generateURL($route, $params)."#rep_".$_TDNReponse->getIdDocument());
			$entree->setTexte('a répondu à');
			$entree->setTitre($_TDNDocument->getTitre());
			$entree->setLnVeilleur($_TDNDocument->getLnAuteur());
			$entree->setSupport('');
			$entree->setLnRubrique($rubrique);
			$entree->setDateEntree($_TDNReponse->getDatePublication());
			$em->persist($entree);

			if ($usr instanceof Nana) {
				$err = $this->upgradePopularite('reponse_nanas', $usr);
	        }

			$em->flush();				

			$this->get('session')->getFlashBag()->add('success', 'Merci d’avoir participé à cet échange' );
		}

		// Affichage de la page
		return $this->redirect($URLmanager->refererURL($request->headers->get('referer')));
	}

	public function reponseVoterAction ($id) {

		$URLmanager = $this->get('tdn.document.url');
		$request = $this->get('request');

        $botList = array('MJ12bot', 'Googlebot');
		$variables['rubrique'] = 'tdn';

        $agent = $_SERVER['HTTP_USER_AGENT'];
        $isBot = false;
        foreach ($botList as $_b) {
            $isBot = $isBot || strpos($agent, $_b);
        }
        if ($isBot === false) {
		    // Récupération de l'entity manager qui va nous permettre de gérer les entités.
		    $em = $this->get('doctrine.orm.entity_manager');      
			// Instanciation du Repository
			$rep = $em->getRepository('TDN\Bundle\CauseuseBundle\Entity\Reponse');
			$reponse = $rep->find($id);

			$reponse->setLikes($reponse->getLikes() + 1);

        	$points = $this->container->getParameter('action_points');
        	$reponse->getLnAuteur()->updatePopularite($points['reponse_nanas_votee']);
		
			$em->flush();
			$this->get('session')->getFlashBag()->add('success', 'Ton vote a été pris en compte.' );
		}

		// Affichage de la page
		return $this->redirect($URLmanager->refererURL($request->headers->get('referer')));
	}

	public function reponseAccepterAction ($idQuestion, $idReponse) {

		$URLmanager = $this->get('tdn.document.url');
		$request = $this->get('request');

		$variables['rubrique'] = 'tdn';

	    // Récupération de l'entity manager qui va nous permettre de gérer les entités.
	    $em = $this->get('doctrine.orm.entity_manager');      
		// Instanciation du Repository
		$rep = $em->getRepository('TDN\Bundle\CauseuseBundle\Entity\Question');
		$question = $rep->find($idQuestion);
		$rep = $em->getRepository('TDN\Bundle\CollectionsauseuseBundle\Entity\Reponse');
		$reponse = $rep->find($idReponse);
		$question->setReponseAcceptee($reponse);
	
       	$points = $this->container->getParameter('action_points');
       	$reponse->getLnAuteur()->updatePopularite($points['reponse_choisie']);

		$em->flush();

		// Affichage de la page
		return $this->redirect($URLmanager->refererURL($request->headers->get('referer')));
	}

	public function rechercheAction ($seed = NULL) {

		$request = $this->get('request');
        if ($request->isMethod('POST')) {
            $seed = $request->get('seed');
        } else {
            $seed = $request->query->get('seed');
        }

        if (strlen($seed) > 2) {
            // Récupération de l'entity manager qui va nous permettre de gérer les entités.
            $em = $this->get('doctrine.orm.entity_manager');      
            $rep = $repository = $em->getRepository('TDN\Bundle\CauseuseBundle\Entity\Question');
            $vars['reponses'] = $rep->findBySeed($seed);            
        } else {
            $this->get('session')->getFlashBag()->add('fail', 'Le moteur de recherche exige au moins trois caractères');
            $vars['reponses'] = false;
        }

        $vars['rubrique'] = 'tdn';
        return $this->render('CauseuseBundle:Page:resultatsRecherche.html.twig', $vars);
    }
}
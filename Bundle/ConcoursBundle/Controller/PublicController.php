<?php

namespace TDN\Bundle\ConcoursBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use TDN\Bundle\DocumentBundle\Controller\PublicController as DocumentController;

use TDN\Bundle\ConcoursBundle\Entity\Concours;
use TDN\Bundle\ConcoursBundle\Entity\ConcoursParticipant;
use TDN\Bundle\ConcoursBundle\Form\Type\ConcoursParticipationType;
use TDN\Bundle\ConcoursBundle\Form\Model\Invitations;
use TDN\Bundle\ConcoursBundle\Form\Type\InvitationType;

use TDN\Bundle\NanaBundle\Entity\Nana;

class PublicController extends DocumentController {

    public function sommaireAction($theme = '')
    {
        $request = $this->get('request');

		$variables = $this->makeSommaire($theme, 'TDN\Bundle\ConcoursBundle\Entity\Concours', 'CONCOURS_PUBLIE');
	    $variables['typesJeu'] = array('TOS' => 'Tirage au sort', 'Q&R' => 'Question/Réponse', 'QCM' => 'QCM');

		$channel = $request->query->get('channel');
		if ($channel === 'ajax') {
			$response = new Response($this->renderView('TDNConseilExpertBundle:Partiels:conseilsListe.html.twig', $variables));
	        $response->headers->set('Content-Type', 'text/html');
	        $response->headers->set('Accept-Charset', 'utf-8');
	        return $response;

		} else {
			// Affichage de la page
	        $variables['titreSommaire'] = 'Concours';
			$variables['routeSommaire'] = 'Concours_sommaire';
			$variables['featuredContenus'] = 
				array_filter(
					$variables['listeContenus'],
					function ($c) { $s = $c->getDateArret(); return $s > new \DateTime;});
			return $this->render('TDNConcoursBundle:Pages:concoursSommaire.html.twig', $variables);
		}
    }

    public function makeSommaire ($rubrique, $entite, $contrainte) {

        $longueurPage = 42;
        $em = $this->get('doctrine.orm.entity_manager');      
        $session = $this->get('session');
        $request = $this->get('request');
        $page = $request->query->get('page');
        $currentRubrique = $session->get('tri-rubrique');
        if (empty($rubrique)) {
            $rubrique = $request->query->get('rubrique');
        }

        if (!empty($rubrique)) {
            $session->set('tri-rubrique', $rubrique);
        } else {
            if (empty($page)) {
                $session->remove('tri-rubrique');
            }
            $rubrique = (!empty($currentRubrique)) ? $currentRubrique : 'tdn';
        }
        $page = ((int)$page === 0) ? 0 : (int)$page - 1;

        $rep = $em->getRepository($entite);
        if ($rubrique == 'tdn' || empty($rubrique)) {
            $variables['listeContenus'] = $rep->findBy(array('statut' => $contrainte), array('idDocument' => 'DESC'), $longueurPage, 1+$page*($longueurPage-1));
            $cardinal = $rep->count();
        } else {
            $variables['listeContenus'] = $rep->findByRubrique($rubrique, $longueurPage, $page, $contrainte);
            $cardinal = $rep->count($rubrique);
        }
        $variables['totalContenus'] = (is_array($cardinal)) ? array_shift($cardinal) : $cardinal ;

        $rep = $em->getRepository('TDN\Bundle\DocumentBundle\Entity\DocumentRubrique');
        $variables['rubriques'] = $rep->findBy(array('parent' => NULL));
        $_objRubrique = $rep->findOneBySlug($rubrique);
        
        $largeurSegment = 4;
        $variables['rubrique'] = $rubrique;
        $variables['nomRubrique'] = ($_objRubrique instanceof DocumentRubrique) ? $_objRubrique->getTitre() : 'Toutes';
        $variables['page'] = $page + 1;
        $variables['derniere'] = ceil($variables['totalContenus'] / $longueurPage);

        return $variables;
    }
    public function concoursAction ($id, $slug) {

		/* Tableau qui va stocker toutes les données à remplacer dans le template twig */
	    $variables = array();  

	    $variables['rubrique'] = 'tdn';

	    // Récupération de l'entity manager qui va nous permettre de gérer les entités.
	    $em = $this->get('doctrine.orm.entity_manager');      

		// Instanciation du Repository
		$repository = $em->getRepository('TDN\Bundle\ConcoursBundle\Entity\Concours');
		$rep_part = $em->getRepository('TDN\Bundle\ConcoursBundle\Entity\ConcoursParticipant');
		$variables['TDNDocument'] = $repository->find($id);
		$typeJeu = $variables['TDNDocument']->getTypeJeuConcours();
		switch ($variables['TDNDocument']->getTypeJeuConcours()) {
			case 'Q&R' :
				$question = $variables['TDNDocument']->getQuestions();	
				foreach($question as $q) {
					$variables['questionnaire'][] = 
						array('question' => $q->getQuestion());
					
				}
				break;

			case 'QCM' :
				$question = $variables['TDNDocument']->getQuestions();	
				foreach($question as $q) {
					$variables['questionnaire'][] = 
						array('question' => $q->getQuestion());
					
				}
				break;

			case 'TOS' :
			default:
				$variables['questionnaire'] = array();
		}

		// Instanciation du formulaire de participation
		$document = new ConcoursParticipant;
		$form = $this->createForm(new ConcoursParticipationType, $document);
		$variables['form'] = $form->createView();

		// Instanciation du formulaire pour les invitations
		// $formInvite = $this->createForm(new InvitationType, new Invitations);
		// $variables['formInvite'] = $formInvite->createView();

		// $rubriques = $variables['TDNDocument']->getRubriques();
		// $variables['rubrique'] = $rubriques[0]->getSlug();

		$variables['auteur'] = $variables['TDNDocument']->getLnAuteur();

		if ($variables['TDNDocument']->getStatut() == 'CONCOURS_ACHEVE') {
			$variables['gagnants'] = $rep_part->findGagnants($id);
		}
		// var_dump($auteur);die;
		
		// récupération des commentaires
		// $rep_comms = $em->getRepository('TDN\Bundle\CommentaireBundle\Entity\Commentaire');
		// $variables['commentaires'] = $rep_comms->findAllThreaded();
		// $variables['nbCommentaires'] = count($variables['commentaires']);
		$variables['commentaires'] = array();
		$variables['nbCommentaires'] = 0;
		$variables['isOuvert'] = ($variables['TDNDocument'] < new \DateTime);

		// Contenus connexes au thème du concours
	    $rep_tags = $em->getRepository('TDN\Bundle\DocumentBundle\Entity\Tag');
	    $rep_docs = $em->getRepository('TDN\Bundle\DocumentBundle\Entity\Document');
	    $sims = $rep_tags->findSimilars($id);
	    $variables['similaires'] = array();
	    foreach($sims as $s) {
	    	$variables['similaires'][] = $rep_docs->find($s['id']);
	    }

		$_rubriques = $variables['TDNDocument']->getRubriques();
		$_thematique = $variables['TDNDocument']->getLnThematique();
		if ($_thematique instanceof DocumentRubrique) {
			$variables['rubriquePrincipale'] = $_thematique;
			$variables['rubrique'] = $_thematique->getSuperSlug();
			$variables['titreRubrique'] = $_thematique->getTitre();
		} else {
			$variables['rubriquePrincipale'] = $_rubriques[0];
			$variables['rubrique'] = $_rubriques[0]->getSuperSlug();
			$variables['titreRubrique'] = $_rubriques[0]->getTitre();
		}

		$variables['rubriqueEntity'] = $_rubriques[0];
		$variables['canonical'] = $this->generateURL('Concours_page', 
			array('id' => $variables['TDNDocument']->getIdDocument(),
				  'slug' => $variables['TDNDocument']->getSlug(),
				  'theme' => $variables['rubriquePrincipale']->getSlug(),
				  'rubrique' => $variables['rubrique']
			));
		$variables['meta_description'] = strip_tags($variables['TDNDocument']->getAbstract());
		$variables['rubrique'] =$variables['rubriquePrincipale'];
		
	    $variables['paths'] = array(
	    	'Article' => 'Redaction_pageArticle',
	    	'ConseilExpert' => 'ConseilExpert_page',
	    	'Question' => 'CauseuseBundle_page',
	    	'Video' => 'VideoBundle_page',
	    	'Dossier' => 'DossierRedaction_page'
	    	);


		// Affichage de la page
		return $this->render('TDNConcoursBundle:Pages:concours_'.strtolower($typeJeu).'.html.twig', $variables);

    }

    public function concoursParticiperAction ($id) {

	    $variables['rubrique'] = 'tdn';

		$request = $this->get('request');
	    // Récupération de l'entity manager qui va nous permettre de gérer les entités.
	    $em = $this->get('doctrine.orm.entity_manager');      
		$usr = $this->get('security.context')->getToken()->getUser();

		// Instanciation du formulaire de participation
		$participation = new ConcoursParticipant;
		$form = $this->createForm(new ConcoursParticipationType, $participation);

		// Instanciation du formulaire pour les invitations
		$invitations = new Invitations;
		$formInvite = $this->createForm(new InvitationType, $invitations);

		if ($request->isMethod('POST')) {
			$form->bindRequest($request);
			if ($form->isValid() || true) {
				$rep_part = $em->getRepository('TDN\Bundle\ConcoursBundle\Entity\ConcoursParticipant');
				if ($usr instanceof Nana) {
					$done = $rep_part->findOneBy(array('lnParticipant' => $usr->getIdNana(), 'lnConcours' => $id));
				} else {
					$done = $rep_part->findOneBy(array('mailParticipant' => $participation->getMailParticipant(), 'lnConcours' => $id));
				}

			    $variables['rubrique'] = 'tdn';
				// Contenus connexes au thème du concours
			    $rep_tags = $em->getRepository('TDN\Bundle\DocumentBundle\Entity\Tag');
			    $rep_docs = $em->getRepository('TDN\Bundle\DocumentBundle\Entity\Document');
			    $sims = $rep_tags->findSimilars($id);
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

				if ($done instanceof ConcoursParticipant) {
					// $this->get('session')->getFlashBag()->add('fail', 'Vous avez déjà participé à ce concours');
					return $this->render('TDNConcoursBundle:Page:concoursErrDouble.html.twig', $variables);
				} else {
					$participantValide = true;
					// Instanciation du Repository
					$repository = $em->getRepository('TDN\Bundle\ConcoursBundle\Entity\Concours');
					$concours = $repository->find($id);
					$typeJeu = $concours->getTypeJeuconcours();

					$participation->setDateParticipation(new \DateTime);
					$participation->setLnConcours($concours);
					if ($usr instanceof Nana) {
						$participation->setLnParticipant($usr);
					} else {
						if ($participation->getMailParticipant() == '') {
							$participantValide = false;
						}
					}

					// Si le concours est un QCM, on doit fusionner les réponses
					// et si c'est un tirage au sort, il n'y a pas de réponse
					if ($typeJeu == "QCM") {
						$reponses = $request->get('qcm_reponse');
						$participation->setReponse(json_encode($reponses));
					} elseif ($typeJeu == "Q&R") {
						// $participation->setReponse("none");
					} else {}

					// Les mails des invités sont fusionnés dans un champ unique
					$formInvite->bindRequest($request);
					$participation->setInvitations(json_encode($invitations->getEmails()));
					$participation->setPoids(0);
					$participation->setGagnant(0);

					$em->persist($participation);
					$em->flush();

					$buzzer = $this->get('tdn.concours.participants');
					$err = $buzzer->forwardConcours($participation);

					$destinataire = ($usr instanceof Nana) ? $usr->getEmail() : $participation->getMailParticipant();
					// Notifier le participant
					$message = \Swift_Message::newInstance();
					$corps['expediteur'] = "Justine";
					$corps['role'] = "Rédaction";
					$corps['destinataire'] = "";
					$corps['dateEnvoi'] = date(' d m Y - H:i:s');
					$corps['titre'] = $concours->getTitre();
					$corps['rebonds'] = $variables['similaires'];
					$corps['paths'] = $variables['paths'];

					$message->setSubject('[Trucdenana.com] Jeu-concours : '.$concours->getTitre())
	       					->setContentType('text/html')
	 						->setFrom('postmaster@trucdenana.com')
	        				->setTo($destinataire)
	        				// ->setTo('michel.cadennes@sens-commun.fr') 
	        				->setBody($this->renderView('TDNConcoursBundle:Mail:participation.html.twig', $corps));
	    			$this->get('mailer')->send($message);

					// $this->get('session')->getFlashBag()->add('success', 'Merci d’avoir participé à ce jeu-concours');
					return $this->render('TDNConcoursBundle:Page:concoursAck.html.twig', $variables);
				}
			} else {
				// Message d'erreur : formulaire non valide
			}
		}

		return $this->redirect($this->generateURL('Concours_sommaire'));
    }

    public function concoursVoterAction ($participant)
    {
    	$variables['rubrique'] = 'tdn';
		$request = $this->get('request');
	    $em = $this->get('doctrine.orm.entity_manager');      
	    $URLmanager = $this->get('tdn.document.url');

		$repository = $em->getRepository('TDN\Bundle\ConcoursBundle\Entity\ConcoursParticipant');
		$reponse = $repository->find($participant);

		$reponse->setVotes( 1+ (integer)$reponse->getVotes());

		$em->flush();

		$this->get('session')->getFlashBag()->add('success', 'Ton vote a bien été enregistré');

		return $this->redirect($URLmanager->refererURL($request->headers->get('referer')));
    }
}

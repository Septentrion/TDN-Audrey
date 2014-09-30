<?php

namespace TDN\Bundle\CommentaireBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\Common\Cache\MemcacheCache as Memcache;

use TDN\Bundle\CommentaireBundle\Entity\Commentaire;
use TDN\Bundle\CommentaireBundle\Form\Type\simpleCommentaireType;
use TDN\Bundle\CoreBundle\Entity\Journal;
use TDN\Bundle\NanaBundle\Entity\Nana;

class PublicController extends Controller
{
    
    public function fluxAction($id) {
	
        // $cache = new Memcache;
        // $_cacheKey = md5('Commentaire:SQL:'.$id);

		// Récupération de l'entity manager qui va nous permettre de gérer les entités.
	    $em = $this->get('doctrine.orm.entity_manager');      
		// Instanciation du formulaire
		$form = $this->createForm(new simpleCommentaireType, new Commentaire);
		$variables['form'] = $form->createView();

		// récupération des commentaires
		$rep_comms = $em->getRepository('TDN\Bundle\CommentaireBundle\Entity\Commentaire');
		$comms = $rep_comms->findBy(array('filDocument' => $id, 'statut' => 1));
		foreach ($comms as $c) {
			$variables['commentaires'][$c->getidThread()][$c->getIdReponse()][] = $c;
		}
		if (array_key_exists('commentaires', $variables)) {
			krsort($variables['commentaires']);
		}
		$variables['idDocument'] = $id;
        return $this->render('TDNCommentaireBundle:Blocs:flux.html.twig', $variables);
    	// return new Response("<div>".count($comms)."</div>");
    }

    public function addAction () {

		$request = $this->get('request');
	    $em = $this->get('doctrine.orm.entity_manager');      
		$rep_comms = $em->getRepository('TDN\Bundle\CommentaireBundle\Entity\Commentaire');
	    $URLmanager = $this->get('tdn.document.url');

		$comment = new Commentaire;

		// Instanciation du formulaire
		$form = $this->createForm(new simpleCommentaireType, $comment);
		$variables['form'] = $form->createView();

		if ($request->getMethod() === 'POST') {
			$form->bindRequest($request);
			$usr= $this->get('security.context')->getToken()->getUser();
			// Instanciation de l'auteur du commentaire
			if ($usr instanceof Nana) {
				$comment->setIdAuteur($usr->getIdNana());
				$comment->setFilAuteur($usr);
			} else {
				$comment->setIdAuteur(NULL);
			}
			// Instanciation du document commenté
			$rep = $em->getRepository('TDN\Bundle\DocumentBundle\Entity\Document');
			$doc = $rep->find($request->get('idDocument'));
			list($route, $rubrique, $params) = $doc->getURLElements();
			$sourceURL = $this->generateURL($route, $params);

			$comment->setFilDocument($doc);
			// Ôter les tags HTML du message
			$tagger = $this->get('tdn.core.urlshortener');
			$nakedComment = strip_tags($comment->getTexteCommentaire());
			$comment->setTexteCommentaire($tagger->urlTagger($nakedComment));

			// Instanciation de la date du commentaire
			$comment->setDatePublication(new \DateTime);
			// Le commentaire est-il une réponse
			$isReponse = $comment->getIdThread();
			if ($isReponse == 0) {
				$comment->setIdThread(uniqid());
				$comment->setIdReponse(0);
			} else {
				$comment->setIdReponse(1);
			}
			$comment->setLike(0);

			$_quarantaine = false;
			$_isspam = false;
			$_antispam = $_POST['antispam'];
			$simpleScanner= $this->get('tdn.core.antispam');
			$spamTraces = $simpleScanner->scan($comment->getTexteCommentaire(), $request->getClientIp(), $_antispam);

			if ($spamTraces['statut'] === 'SPAM') {
				$comment->setStatut(0);
				// $em->persist($comment);
				// $em->flush();

				// Notification
				$admins = $this->container->getParameter('admin_notifications');
				$expediteurs = $this->container->getParameter('mail_expediteur');				
				$message = \Swift_Message::newInstance();
				$corps['expediteur'] = "Administrateur";
				$corps['role'] = "Système";
				$corps['destinataire'] = "modérateur";
				$corps['dateEnvoi'] = date(' d m Y - H:i:s');
				// Spécifiques
				$corps['titre'] = $doc->getTitre();
				$corps['url'] = $sourceURL;
				$corps['commentaire'] = $comment->getTexteCommentaire();
				$corps['idCommentaire'] = $comment->getIdCommentaire();
				$corps['isSuspect'] = true;
				$corps['isSpam'] = "<ul>";
				foreach ($spamTraces['fails'] as $critere => $motif) {
					$corps['isSpam'] .= "<li><strong>$critere</strong> : $motif</li>";
				}
				$corps['isSpam'] .= "</ul>";
				$corps['IP'] = $request->getClientIp();

				$message->setSubject('[TDN SPAM] Rapport de '.strtolower($spamTraces['statut']).' sur une page')
						->setContentType('text/html')
	        			->setFrom($expediteurs['admin'])
	        			->addTo($this->container->getParameter('mail_moderation_1'))
	        			->setBody(
	            			$this->renderView('CommentaireBundle:Mail:nouveauCommentaire.html.twig', $corps),
	            			'text/html'
	            		);
			    // $this->get('mailer')->send($message);

			} else {
				if ($spamTraces['statut'] === 'VALIDE') {
					// Signaler dans le journal & informer l'auteur
					$entree = new Journal;
					if (is_object($usr)) $entree->setLnActeur($usr);
					$entree->setAction('COMMENTAIRE');
					$entree->setURL($sourceURL);
					$entree->setTexte('a ajouté un commentaire à');
					$entree->setTitre($doc->getTitre());
					$entree->setLnVeilleur($doc->getLnAuteur());
					$entree->setSupport('');
					$entree->setLnRubrique($rubrique);
					$entree->setDateEntree($comment->getDatePublication());
					$em->persist($entree);

					if ($usr instanceof Nana) {
			        	$points = $this->container->getParameter('action_points');
			        	$usr->updatePopularite($points['commentaire']);
			        }

			        $followers = $rep_comms->findBy(array('filDocument' => $doc->getIdDocument(), 'abonne' => 1));
					foreach($followers as $_f) {
						if (!is_null($_f->getFilAuteur())) {
							$adresseAbonne = $_f->getFilAuteur()->getEmail();
							$pseudoAbonne = $_f->getFilAuteur()->getUsername();
							// Notification
							$message = \Swift_Message::newInstance();
							$corps['expediteur'] = "Justine";
							$corps['role'] = "Rédaction";
							$corps['destinataire'] = $pseudoAbonne;
							$corps['dateEnvoi'] = date(' d m Y - H:i:s');
							// Spécifiques
							$corps['titre'] = $doc->getTitre();
							$corps['url'] = $sourceURL;
							$corps['commentaire'] = $comment->getTexteCommentaire();
							$corps['idCommentaire'] = $comment->getIdCommentaire();

							$message->setSubject('[TDN] Nouveau commentaire sur une page que tu suis')
									->setContentType('text/html')
				        			->setFrom('redaction@trucdenana.com')
				        			->addTo($adresseAbonne)
				        			->setBody(
				            			$this->renderView('CommentaireBundle:Mail:followersCommentaire.html.twig', $corps),
				            			'text/html'
				            		);
						    $this->get('mailer')->send($message);							
						}
					}
				}

				$comment->setStatut(($spamTraces['statut'] == 'SUSPECT') ? 0 : 1);
				$em->persist($comment);
				$em->flush();

				// Notification
				$admins = $this->container->getParameter('admin_notifications');
				$expediteurs = $this->container->getParameter('mail_expediteur');				
				$message = \Swift_Message::newInstance();
				$corps['expediteur'] = "Administrateur";
				$corps['role'] = "Système";
				$corps['destinataire'] = "modérateur";
				$corps['dateEnvoi'] = date(' d m Y - H:i:s');
				// Spécifiques
				$corps['titre'] = $doc->getTitre();
				$corps['url'] = $sourceURL;
				$corps['commentaire'] = $comment->getTexteCommentaire();
				$corps['idCommentaire'] = $comment->getIdCommentaire();
				$corps['isSuspect'] = (0 == $comment->getStatut());
				if (isset($spamTraces['fails'])) {
					$corps['isSpam'] = "<ul>";
					foreach ($spamTraces['fails'] as $critere => $motif) {
						if ($motif != '') {
							$corps['isSpam'] .= "<li><strong>$critere</strong> : $motif</li>";
						}
					}
					$corps['isSpam'] .= "</ul>";					
				} else {
					$corps['isSpam'] = "";
				}
				$corps['IP'] = $request->getClientIp().' : '.$spamTraces['fails']['geoIP'];

				$message->setSubject('[TDN] Commentaire '.strtolower($spamTraces['statut']).' sur une page')
						->setContentType('text/html')
	        			->setFrom($expediteurs['admin'])
	        			->setBody(
	            			$this->renderView('CommentaireBundle:Mail:nouveauCommentaire.html.twig', $corps),
	            			'text/html'
	            		);
				foreach($admins['redaction'] as $destinataire) {
					$message->addTo($destinataire);
				}
			    $this->get('mailer')->send($message);

			    if ($spamTraces['statut'] == 'SUSPECT') {
					$this->get('session')->getFlashBag()->add('fail', 'Ton commentaire a bien été enregistré mais sera modéré, soit parce que tu n’as pas répondu à la question anti-spam, soit parce des expressions typiques ont été trouvées dans ton message');
			    } else {
			    	$this->get('session')->getFlashBag()->add('success', 'Merci d’avoir commenté cet article');
			    }
			}
		}
	
		return $this->redirect($URLmanager->refererURL($request->headers->get('referer')));
    }

    public function voteForAction () {

	    $em = $this->get('doctrine.orm.entity_manager');      
	    $URLmanager = $this->get('tdn.document.url');
		$request = $this->get('request');

		$comment = new Commentaire;

		// Instanciation du formulaire
		$form = $this->createForm(new simpleCommentaireType, $comment);
		$variables['form'] = $form->createView();

		if ($request->getMethod() === 'GET') {
			$idCommentaire = $request->get('id');
			// Instanciation du document commenté
			$rep = $em->getRepository('TDN\Bundle\CommentaireBundle\Entity\Commentaire');
			$comment = $rep->find($idCommentaire);
			$comment->setLike(1 + $comment->getLike());

			$em->persist($comment);
			$em->flush();
		}

		return $this->redirect($URLmanager->refererURL($request->headers->get('referer')));

    }
}

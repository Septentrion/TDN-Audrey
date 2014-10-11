<?php

namespace TDN\Bundle\VideoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

use TDN\Bundle\VideoBundle\Form\Type\VideoType;
use TDN\Bundle\VideoBundle\Entity\Video;

use TDN\Bundle\DocumentBundle\Controller\PublicController as DocumentController;
use TDN\Bundle\DocumentBundle\Entity\DocumentRubrique;
use TDN\Bundle\DocumentBundle\Entity\DocumentType;
use TDN\Bundle\DocumentBundle\Entity\DocumentRubriqueRepository;



class PublicController extends DocumentController {
	
	public function videoProposerAction () {

		$request = $this->get('request');

		$variables['rubrique'] = 'tdn';

		$em = $this->get('doctrine.orm.entity_manager');

		// Instanciation du formulaire
		$_TDNDocument = new Video;
		$form = $this->createForm(new VideoType, $_TDNDocument);
		$variables['form'] = $form->createView();
		if ($request->getMethod() == "POST") {
			$form->bind($request);
			if ($form->isValid()) {
				$url = $_TDNDocument->getIdVideo();
				$_importable = false;
				$valideURLData = $_TDNDocument->parseHebergeurURL($url);
				if (!$valideURLData) {
						$this->get('session')
							 ->getFlashBag()
							 ->add('fail', 'L’URL de la video n’est pas valide');
							 $_importable = $_importable || false;
				} else {
					$domaine = array($valideURLData[2]);
					$_kwown = array_intersect($domaine, array('youtube', 'dailymotion', 'vimeo'));
					if (empty ($_kwown)) {
						$_TDNDocument->setIdVideo(NULL);
						$_code = $_TDNDocument->getCodeIntegration();
						if (!empty($_code)) {
							$_importable = true;
						} else {
							$_importable = false;
							$this->get('session')
								 ->getFlashBag()
								 ->add('fail', 'Tu dois donner soit une URL valide, soit un code d’intégration.');
						}
					} else {
						$idVideo = $_TDNDocument->getIdVideo();
						$videoURLData = $_TDNDocument->parseHebergeurURL($idVideo);
						if (!is_array($videoURLData)) {
							$this->get('session')
								 ->getFlashBag()
								 ->add('fail', 'L’URL de la video n’est pas valide');
								 $_importable = $_importable || false;
						} else {
							$_TDNDocument->setIdHebergeur($videoURLData[2]);
							$_importable = $_importable || $_TDNDocument->videoSegment($videoURLData[3]);
							if (!$_importable) {
							$this->get('session')
								 ->getFlashBag()
								 ->add('fail', 'La vidéo n’a pas pu être trouvée chez l’hébergeur');
							}
						}
					}
				}
				if ($_importable) {
					$usr= $this->get('security.context')->getToken()->getUser();
					// print_r($usr); die;		
					// $_TDNDocument->setTitre($OriginalTitle);
					$_TDNDocument->setSlug('');
					$_TDNDocument->setLnAuteur($usr);
					$_TDNDocument->setLikes(0);
					$_TDNDocument->setHits(0);
					$_TDNDocument->setParams("{}");
					$_TDNDocument->setTags("");
					$_TDNDocument->setCommentThread(new \Doctrine\Common\Collections\ArrayCollection());
					$_TDNDocument->setDatePublication(new \DateTime);
					$_TDNDocument->setDateModification($_TDNDocument->getDatePublication());
					$_TDNDocument->setVersion("1.0");

					$_TDNDocument->setStatut('VIDEO_PROPOSEE');
					if ($this->get('security.context')->isGranted('ROLE_JOUNALISTE')) {
						$this->get('session')->getFlashBag()->add('success', 'Cette vidéo est enregistrée et a le statut de brouillon');
					} else {
		 				$this->get('session')->getFlashBag()->add('success', 'Merci d’avoir proposé cette vidéo. La rédaction va la valider au plus vite');
					}

					$em->persist($_TDNDocument);
					$em->flush();

					// Notification
				$admins = $this->container->getParameter('admin_notifications');
				$expediteurs = $this->container->getParameter('mail_expediteur');				
					$message = \Swift_Message::newInstance();
					$corps['expediteur'] = "Justine";
					$corps['role'] = "Rédaction";
					$corps['destinataire'] = "Justine";
					$corps['dateEnvoi'] = date(' d m Y - H:i:s');
					// Spécifiques
					$corps['pseudo'] = $usr->getUsername();
					$corps['titre'] = $_TDNDocument->getTitre();
					if (!empty($url)) { $corps['url'] = $url; }

					$message->setSubject('[TDN] '.$usr->getEmail().' propose une vidéo' )
							->setContentType('text/html')
		        			->setFrom($usr->getEmail())
		        			->addTo($usr->getEmail())
		        			->setBody(
		            			$this->renderView('TDNVideoBundle:Mail:propositionVideo.html.twig', $corps),
		            			'text/html'
		            		);
					foreach($admins['redaction'] as $destinataire) {
						$message->addTo($destinataire);
					}
				    $this->get('mailer')->send($message);

					return $this->redirect($this->generateURL('VideoBundle_sommaire'));									
				} else {
					return $this->render('TDNVideoBundle:Page:videoProposition.html.twig', $variables);
				}
			} else {
				return $this->generateURL('VideoBundle_sommaire');									
			}
		}

		return $this->render('TDNVideoBundle:Page:videoProposition.html.twig', $variables);
	}

	public function videoSommaireAction ($rubrique = '') {

        $request = $this->get('request');

		$variables = $this->makeSommaire($rubrique, 'TDN\Bundle\VideoBundle\Entity\Video', 'VIDEO_PUBLIEE');

		$channel = $request->query->get('channel');
		if ($channel === 'ajax') {
			$response = new Response($this->renderView('TDNVideoBundle:Partiels:videosListe.html.twig', $variables));
	        $response->headers->set('Content-Type', 'text/html');
	        $response->headers->set('Accept-Charset', 'utf-8');
	        return $response;

		} else {
			// Affichage de la page
	        $variables['titreSommaire'] = 'Vidéos';
			$variables['routeSommaire'] = 'Video_sommaire';
			return $this->render('TDNVideoBundle:Pages:videoSommaire.html.twig', $variables);
		}
	}


	public function videoAction ($theme, $rubrique, $slug, $id) {
	/* Tableau qui va stocker toutes les données à remplacer dans le template twig */
	    $variables = array();  
	    // Récupération de l'entity manager qui va nous permettre de gérer les entités.
	    $em = $this->get('doctrine.orm.entity_manager');      
		// Instanciation du Repository
		$rep_video = $em->getRepository('TDN\Bundle\VideoBundle\Entity\Video');
		$video = $rep_video->find($id);
		$rep_commentaires = $em->getRepository('TDN\Bundle\CommentaireBundle\Entity\Commentaire');
		$variables['commentaires'] = $rep_commentaires->findByFilDocument($id);
		
		$hebergeur = $video->getIdHebergeur();
		switch ($hebergeur) {
			case 'dailymotion':
			case '2':
				$params = $video->getParams();
				$params = json_decode($params);
				$variables['codeIntegration'] = $video->getCodeIntegration();
				$variables['codeIntegration'] = str_replace('480', '360', $variables['codeIntegration']);
				$variables['codeIntegration'] = str_replace('360', '203', $variables['codeIntegration']);
				$minutes = floor($video->getDuree()/60);
				$secondes = $video->getDuree() - ($minutes * 60);
				$variables['duree'] = $minutes."' ".$secondes."\"";
				break;
			case 'vimeo':
			case '1':
				$ID = $video->getIdVideo();
				$variables['codeIntegration'] = "<iframe src='http://player.vimeo.com/video/$ID' width='360' frameborder='0' webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>";
				break;
			case 'youtube':
			case '0':
				$ID = $video->getIdVideo();
				$variables['codeIntegration'] = "<iframe width='360' height='270' src='https://www.youtube.com/embed/$ID?rel=0' frameborder='0' allowfullscreen></iframe>";
				break;
			default:
				$variables['codeIntegration'] = stripslashes($video->getCodeIntegration());
		}

		$video->updateHits();
		$em->flush();

		$variables['TDNDocument'] = $video;
		$_rubriques = $video->getRubriques();
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

		$variables['rubriqueEntity'] = $_rubriques[0];
		$variables['canonical'] = $this->generateURL('Video_page', 
			array('id' => $video->getIdDocument(),
				  'slug' => $video->getSlug(),
				  'theme' => $variables['rubriquePrincipale']->getSlug(),
				  'rubrique' => $variables['rubrique']
			));
		$variables['meta_description'] = strip_tags($video->getAbstract());
		$variables['rubrique'] = $rubrique;
		
	    $variables['paths'] = array(
	    	'Article' => 'Redaction_pageArticle',
	    	'ConseilExpert' => 'ConseilExpert_page',
	    	'Question' => 'CauseuseBundle_page',
	    	'Video' => 'VideoBundle_page',
	    	'Dossier' => 'DossierRedaction_page'
	    	);

		// Documents proches (pour aller plus loin)
	    $rep_tags = $em->getRepository('TDN\Bundle\DocumentBundle\Entity\Tag');
	    $sims = $rep_tags->findSimilars($id);

	    $rep_docs = $em->getRepository('TDN\Bundle\DocumentBundle\Entity\Document');
	    $variables['similaires'] = array();
	    foreach($sims as $s) {
	    	$variables['similaires'][] = $rep_docs->find($s['id']);
	    }

		// Affichage de la page
		return $this->render('TDNVideoBundle:Pages:video.html.twig', $variables);
	}
}

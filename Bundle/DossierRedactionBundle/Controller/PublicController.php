<?php

namespace TDN\Bundle\DossierRedactionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

use TDN\Bundle\DocumentBundle\Controller\PublicController as MainPublicController;
use TDN\Bundle\DocumentBundle\Entity\DocumentRubrique;
use TDN\Bundle\DocumentBundle\Entity\DocumentType;
use TDN\Bundle\DocumentBundle\Entity\DocumentRubriqueRepository;


class PublicController extends MainPublicController {
	
	public function sommaireAction ($theme = '') {

       $request = $this->get('request');

		$variables = $this->makeSommaire($theme, 'TDN\Bundle\DossierRedactionBundle\Entity\Dossier', 'DOSSIER_PUBLIE');

		$channel = $request->query->get('channel');
		if ($channel === 'ajax') {
			$response = new Response($this->renderView('TDNDossierRedactionBundle:Partiels:dossiersListe.html.twig', $variables));
	        $response->headers->set('Content-Type', 'text/html');
	        $response->headers->set('Accept-Charset', 'utf-8');
	        return $response;

		} else {
			// Affichage de la page
	        $variables['titreSommaire'] = 'Dossiers de la rédaction';
			$variables['routeSommaire'] = 'DossierRedaction_sommaire';
			for ($i = 0 ; $i < 3; $i++) if (!empty($variables['listeContenus'])) {
				$variables['featuredContenus'][] = array_shift($variables['listeContenus']);
			}
			return $this->render('TDNDossierRedactionBundle:Pages:dossierSommaire.html.twig', $variables);
		}
	}

	public function dossierAction ($rubrique, $slug, $id) {
	    $variables = array();  
		$variables['rubrique'] = 'tdn';

	    // Récupération de l'entity manager qui va nous permettre de gérer les entités.
	    $em = $this->get('doctrine.orm.entity_manager');    
		$rep_dossier = $em->getRepository('TDN\Bundle\DossierRedactionBundle\Entity\Dossier');
		$rep_feuillet = $em->getRepository('TDN\Bundle\DocumentBundle\Entity\Document');
		$rep_commentaires = $em->getRepository('TDN\Bundle\CommentaireBundle\Entity\Commentaire');

		$variables['dossier'] = $rep_dossier->find($id);
		$variables['feuillets'] = $rep_feuillet->findBy(array('lnDossier' => $id), array('ordreDossier' => 'ASC'));
		$variables['compte_parties'] = count($variables['feuillets']);

		$rubriques = $variables['dossier']->getRubriques();
		$variables['rubrique'] = $rubriques[0]->getSuperSlug();

		$variables['dossier']->updateHits();
		$em->flush();

		$variables['canonical'] = $this->generateURL('DossierRedaction_dossier', 
			array('id' => $variables['dossier']->getIdDocument(),
				  'slug' => $variables['dossier']->getSlug(),
				  'rubrique' => $rubriques[0]->getSlug())
			);
		$variables['meta_description'] = strip_tags($variables['dossier']->getAbstract());

		// Documents proches (pour aller plus loin)
	    $rep_tags = $em->getRepository('TDN\Bundle\DocumentBundle\Entity\Tag');
	    $statement = $this->get('database_connection');	    
	    $sims = $rep_tags->findSimilars($id);

	    $rep_docs = $em->getRepository('TDN\Bundle\DocumentBundle\Entity\Document');
	    $variables['similaires'] = array();
	    foreach($sims as $s) {
	    	$variables['similaires'][] = $rep_docs->find($s['id']);
	    }

	    $variables['paths'] = array(
	    	'Article' => 'Article_page',
	    	'ConseilExpert' => 'ConseilExpert_page',
	    	'Question' => 'Question_page',
	    	'Video' => 'Video_page',
	    	'Dossier' => 'DossierRedaction_page'
	    	);

		// Affichage de la page
		return $this->render('TDNDossierRedactionBundle:Page:dossier.html.twig', $variables);

	}

	public function feuilletAction ($rubrique, $slug, $id) {
	    $variables = array();  
		$variables['rubrique'] = 'tdn';

	    $em = $this->get('doctrine.orm.entity_manager');    
		$rep_dossier = $em->getRepository('TDN\Bundle\DossierRedactionBundle\Entity\Dossier');
		$rep_feuillet = $em->getRepository('TDN\Bundle\DocumentBundle\Entity\Document');
		$rep_commentaires = $em->getRepository('TDN\Bundle\CommentaireBundle\Entity\Commentaire');
		$rep_main = $em->getRepository('TDN\Bundle\RedactionBundle\Entity\Article');
		$rep_sel = $em->getRepository('TDN\Bundle\RedactionBundle\Entity\SelectionShopping');
		$rep_vid = $em->getRepository('TDN\Bundle\VideoBundle\Entity\Video');
		$rep_cons = $em->getRepository('TDN\Bundle\ConseilExpertBundle\Entity\ConseilExpert');

		// Rechercher l'article
		$piece = $rep_main->find($id);
		$variables['type'] = "article";
		if (!is_object($piece)) {
			$piece = $rep_sel->find($id);
			$variables['type'] = "selection";
		}
		if (!is_object($piece)) {
			$piece = $rep_cons->find($id);
			$variables['type'] = "conseil";
		}
		if (!is_object($piece)) {
			$piece = $rep_vid->find($id);
			$variables['type'] = "video";
			if (is_object($piece)) {
						$hebergeur = $piece->getIdHebergeur();
				switch ($hebergeur) {
					case 'dailymotion':
					case '2':
						$params = $piece->getParams();
						$params = json_decode($params);
						$variables['codeIntegration'] = $piece->getCodeIntegration();
						$variables['codeIntegration'] = str_replace('480', '360', $variables['codeIntegration']);
						$variables['codeIntegration'] = str_replace('360', '203', $variables['codeIntegration']);
						$minutes = floor($piece->getDuree()/60);
						$secondes = $piece->getDuree() - ($minutes * 60);
						$variables['duree'] = $minutes."' ".$secondes."\"";
						break;
					case 'vimeo':
					case '1':
						$ID = $piece->getIdVideo();
						$variables['codeIntegration'] = "<iframe src='http://player.vimeo.com/video/$ID' width='360' frameborder='0' webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>";
						break;
					case 'youtube':
					case '0':
						$ID = $piece->getIdVideo();
						$variables['codeIntegration'] = "<iframe width='360' height='270' src='https://www.youtube.com/embed/$ID?rel=0' frameborder='0' allowfullscreen></iframe>";
						break;
					default:
						$variables['codeIntegration'] = stripslashes($piece->getCodeIntegration());
				}
			}
		}
		$variables['main'] = $piece;
		$dossier = $variables['main']->getLnDossier();		
		$variables['feuillets'] = $rep_feuillet->findBy(array('lnDossier' => $dossier->getIdDocument()), array('ordreDossier' => 'ASC'));

		$rubriques = $variables['main']->getLnDossier()->getRubriques();
		$variables['rubrique'] = $rubriques[0]->getSuperSlug();

		$variables['main']->updateHits();
		$em->flush();

		$variables['canonical'] = $this->generateURL('DossierRedaction_feuillet', 
			array('id' => $variables['main']->getIdDocument(),
				  'slug' => $variables['main']->getSlug(),
				  'rubrique' => $rubriques[0]->getSlug())
			);
		$variables['meta_description'] = strip_tags($variables['main']->getAbstract());

		// $variables['auteur'] = "Emmaaa";
		// $variables['age'] = "13 ans";
		// $variables['expert'] = "Justine Andanson";
		// $variables['role'] = "Journaliste";
		// $variables['datePublication'] = "10/10/2012";
		// $variables['nomDeRubrique'] = "beauté";
		// $variables['fullURL'] = "/$rubrique/article/$slug,$id,full";
		
		// Affichage de la page
		return $this->render('TDNDossierRedactionBundle:Page:dossierFeuillet.html.twig', $variables);

	}
}

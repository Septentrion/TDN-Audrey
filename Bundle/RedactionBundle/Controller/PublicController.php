<?php

namespace TDN\Bundle\RedactionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use TDN\Bundle\DocumentBundle\Controller\PublicController as MainPublicController;
use TDN\Bundle\DocumentBundle\Entity\DocumentRubrique;
use TDN\Bundle\CommentaireBundle\Entity\Commentaire;
use TDN\Bundle\RedactionBundle\Entity\Article;
use TDN\Bundle\NanaBundle\Entity\Nana;

class PublicController extends MainPublicController {
    
	protected function cutText($completeArticle) {
		
	}

    public function articleAction($theme, $rubrique, $slug, $id) {

	    $em = $this->get('doctrine.orm.entity_manager');      
		$repository = $em->getRepository('TDN\Bundle\RedactionBundle\Entity\Article');
	
		/* Tableau qui va stocker toutes les données à remplacer dans le template twig */
	    $variables = array();  
	    $variables['rubrique'] = 'tdn';

		$variables['TDNDocument'] = $repository->find($id);

		if (!($variables['TDNDocument'] instanceof Article)) {
			$this->get('session')->getFlashBag()->add('fail', 'Désolé, ette page n’existe pas');
			return $this->redirect($this->generateURL('Core_home'));
		} else {
			// Suppression du flag de veille pour les notifications myTDN
	    	$request = $this->get('request');
	    	$entreeID = $request->query->get('razj');
	    	if (!empty($entreeID)) {
				$rep_journal = $em->getRepository('TDN\CoreBundle\Entity\Journal');
				$entree = $rep_journal->find($entreeID);
				$entree->setLnVeilleur(NULL);
	    	}

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

			$variables['TDNDocument']->updateHits();
			$em->flush();

			$variables['canonical'] = $this->generateURL('Article_page', 
				array('id' => $variables['TDNDocument']->getIdDocument(),
					  'slug' => $variables['TDNDocument']->getSlug(),
					  'theme' => $variables['rubriquePrincipale']->getSlug() ,
					  'rubrique' => $variables['rubrique'])
				);
			$variables['meta_description'] = strip_tags($variables['TDNDocument']->getAbstract());
			
			// Documents proches (pour aller plus loin)
		    // $rep_tags = $em->getRepository('TDN\Bundle\DocumentBundle\Entity\Tag');
		    // $statement = $this->get('database_connection');	    
		    // $sims = $rep_tags->findSimilars($id);

		    // $rep_docs = $em->getRepository('TDN\Bundle\DocumentBundle\Entity\Document');
		    // $variables['similaires'] = array();
		    // foreach($sims as $s) {
		    // 	$variables['similaires'][] = $rep_docs->find($s['id']);
		    // }

		    $variables['paths'] = array(
		    	'Article' => 'RedactionBundle_article',
		    	'ConseilExpert' => 'ConseilExpert_conseil',
		    	'Question' => 'CauseuseBundle_conversation',
		    	'Video' => 'VideoBundle_video',
		    	'Dossier' => 'DossierRedaction_dossier'
		    	);

			// Affichage de la page
			return $this->render('TDNRedactionBundle:Pages:article.html.twig', $variables);			
		}
    }

	public function sommaireAction ($theme = '', $rubrique = '') {

        $request = $this->get('request');

		$variables = $this->makeSommaire($rubrique, 'TDN\Bundle\RedactionBundle\Entity\Article', 'ARTICLE_PUBLIE');

		$channel = $request->query->get('channel');
		if ($channel === 'ajax') {
			$response = new Response($this->renderView('TDNRedactionBundle:Partiels:articlesListe.html.twig', $variables));
	        $response->headers->set('Content-Type', 'text/html');
	        $response->headers->set('Accept-Charset', 'utf-8');
	        return $response;

		} else {
			// Affichage de la page
	        $variables['titreSommaire'] = 'Articles de la rédaction';
			$variables['routeSommaire'] = 'Article_sommaire';
			return $this->render('TDNRedactionBundle:Pages:articleSommaire.html.twig', $variables);
		}
	}


}

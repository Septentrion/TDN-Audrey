<?php

namespace TDN\Bundle\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class JournalController extends Controller {

    public function filJournalAction ($limite = 5) {

        // Récupération de l'entity manager qui va nous permettre de gérer les entités.
        $em = $this->get('doctrine.orm.entity_manager');      

        // Récupération de la question la plus récente
        $rep = $em->getRepository('TDN\Bundle\CoreBundle\Entity\Journal');
        $variables['entrees'] = $rep->findMostRecent($limite);

		return $this->render('TDNCoreBundle:Partiels:filJournal.html.twig', $variables);
	}

	public function razAlertesAction () 
	{
		$em = $this->get('doctrine.orm.entity_manager');      
        $rep_journal = $em->getRepository('TDN\Bundle\CoreBundle\Entity\Journal');
		$security = $this->get('security.context');
		$URLmanager = $this->get('tdn.document.url');
		$request = $this->get('request');

		$veilleur = $security->getToken()->getUser();
		$actions = $request->get('actions'); 
		
		switch ($actions) {
			case 'messages':
				$keyActions = array('COMMENTAIRE', 'REPONSE', 'REPONSE_COMMENTAIRE');
				break;
			
			case 'likes':
				$keyActions = array('AIME');
				break;
			
			case 'follows':
				$keyActions = array('SUIT');
				break;
			
			case 'upgrade':
				$keyActions = array('UPGRADE');
				break;
			
			default:
				break;
		}
		
		try {
			$entrees = $rep_journal->findMyJournal($veilleur->getIdNana(), $keyActions);
			foreach ($entrees as $e) {
				$e->setLnVeilleur(NULL);
			}
		} catch (\Exception $e) {
				$this->get('session')->getFlashBag()->add('fail', 'Un erreur a empêché l’effacement de la liste');
		}

		$em->flush();

		return $this->redirect($URLmanager->refererURL($request->headers->get('referer')));
	}
}
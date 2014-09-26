<?php

namespace TDN\Bundle\NanaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use TDN\CoreBundle\Entity\Journal;

class MyTDNController extends Controller {

    public function tiroirAction () {

    	// $me = $this->getUser();
        $em = $this->get('doctrine.orm.entity_manager');      
        $rep_journal = $em->getRepository('TDN\Bundle\CoreBundle\Entity\Journal');
		$security = $this->container->get('security.context');


    	if ($security->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
			$me = $security->getToken()->getUser();
    		$slots['username'] = $me->getUsername();
            $slots['avatar'] = $me->getLnAvatar();
    		foreach ($me->getRoles() as $r) {
    			$my_roles[] = $r->getName();
    		}
    		$slots['statut'] = "(".implode(',', $my_roles).")";
            $slots['me'] = $me;

            $id = $me->getIdNana();
            $slots['messagesActions'] = array('COMMENTAIRE', 'REPONSE', 'REPONSE_COMMENTAIRE');
            $slots['messages'] = $rep_journal->findMyJournal($id, $slots['messagesActions']);
            $slots['likeActions'] = array('AIME');
            $slots['likes'] = $rep_journal->findMyJournal($id, $slots['likeActions']);
            $slots['followActions'] = array('SUIT');
            $slots['follows'] = $rep_journal->findMyJournal($id, $slots['followActions']);

            $alertes = $me->getFilAlertes();
            $slots['gain'] = 0;
            foreach($alertes as $entree) {
                $action = $entree->getAction();
                switch ($action) {
                    case 'UPGRADE':
                        $slots['gain'] += 1;
                        break;
                    
                    default:
                        break;
                }
            }
        } else {
            $slots['messages'] = array();
            $slots['likes'] = array();
            $slots['follows'] = array();
        }
        
        $slots['perso'] = "";
        $slots['activite'] = "";
        $slots['relations'] = "";

		return $this->render('TDNNanaBundle:Partiels:myTDN.html.twig', $slots);
	}
}
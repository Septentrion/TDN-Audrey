<?php

namespace TDN\Bundle\NanaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use TDN\Bundle\NanaBundle\Entity\Nana;
use TDN\Bundle\NanaBundle\Entity\NanaRoles;
use TDN\Bundle\NanaBundle\Entity\NanaPortraitImageProxy;
use TDN\Bundle\NanaBundle\Form\Type\completeProfilType;
use TDN\Bundle\NanaBundle\Entity\NanaHobby;
use TDN\Bundle\NanaBundle\Form\Type\HobbyType;

use TDN\Bundle\CoreBundle\Entity\Journal;

use TDN\Bundle\ImageBundle\Form\Type\simpleImageType;
use TDN\Bundle\ImageBundle\Entity\Image;

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

    /**
    *
    * myIdentiteAction
    *
    * Contrôleur gérant la section 'Identité' du profil
    *
    * @version 0.0.1
    *
    * @param string $action — mode d'action : afficher|mofidier
    *
    * @return Response
    *
    */
    public function myIdentiteAction ($action) {

        // Récupération de l'entity manager qui va nous permettre de gérer les entités.
        $em = $this->get('doctrine.orm.entity_manager');      
        $rep_nana = $repository = $em->getRepository('TDN\Bundle\NanaBundle\Entity\Nana');
        $rep_journal = $repository = $em->getRepository('TDN\Bundle\CoreBundle\Entity\Journal');
        
        $variables = array();  
        $vars['me'] = $this->container->get('security.context')->getToken()->getUser();
        $vars['likes'] = $rep_journal->likes($vars['me']->getIdNana());

        if ($action === 'modifier') {
            return $this->_updateMyIdentiteAction($vars);
        } else {
            return $this->_showMyIdentiteAction($vars);
        }

    }

    /**
    *
    * _showMyIdentiteAction
    *
    * Préparation pour l'affichage du formulaire 'Identité'
    *
    * @version 1.1.1
    *
    * @param array $vars — Données pour l'affichage
    *
    * @return Response
    *
    **/
    private function _showMyIdentiteAction (array $vars) {


       // Formulaire pour les données personnelles
        $vars['form'] = $this->createForm(new completeProfilType(), $vars['me'])->createView();
        // Formulaire pour changer d'avatar
        $vars['form_avatar'] = $this->createForm(new simpleImageType(), new Image)->createView();

        return $this->render('TDNNanaBundle:Partiels:myProfil_Identite.html.twig', $vars);
    }

    /**
    *
    * _updateMyIdentiteAction
    *
    * Traitement du formulaire d'identité
    *
    * @version 1.0.1
    *
    * @param array $vars — Données pour l'affichage
    *
    * @return Response
    *
    **/
    private function _updateMyIdentiteAction (array $vars) {

        $request = $this->get('request');

        $variables['rubrique'] = 'tdn';

       // Récupération de l'entity manager qui va nous permettre de gérer les entités.
        $em = $this->get('doctrine.orm.entity_manager');
        $rep_nana = $em->getRepository('TDN\Bundle\NanaBundle\Entity\Nana');
        $usr= $this->get('security.context')->getToken()->getUser();
        $nana = $rep_nana->find($usr->getIdNana());
        $ancienPassword = $usr->getPassword();
        $ancienUsername = $usr->getUsername();
        $ancienEmail = $usr->getEmail();
        // Le profil était-il déjà complet
        $ancienProfilComplet = $this->isProfileComplete($usr);
        $ancienNewsletter = $usr->getNewsletter();
        $ancienOffresPartenaires = $usr->getOffresPartenaires();

        $newParameters = $request->request->get('nana_complete_profil');
        $newUsername = $newParameters['username'];
        $newEmail = $newParameters['email'];

        if (!empty($newUsername) && ($ancienUsername != $newUsername)) {
            $homonyme = $rep_nana->findOneByUsername($newUsername);
            if ($homonyme instanceof Nana) {
                if ($homonyme->getIdNana() == $nana->getIdNana()) {
                    unset($homonyme);
                } else {
                    $this->get('session')->getFlashBag()->add('fail', 'Le pseudo <strong>'.$newUsername.'</strong> est déjà utilisé sur le site');
                }                    
            } else {
                unset($homonyme);
            }
        }

        if (empty($newEmail)) {
            $newParameters['email'] = $ancienEmail;
            $this->get('session')->getFlashBag()->add('fail', 'Une adresse électronique est obligatoire');
        } elseif ($ancienEmail != $newEmail) {
            $homomail = $rep_nana->findOneByEmail($newEmail);
            if ($homomail instanceof Nana) {
                if ($homomail->getIdNana() == $nana->getIdNana()) {
                    unset($homomail);
                } else {
                    $this->get('session')->getFlashBag()->add('fail', 'L’email <strong>'.$newEmail.'</strong> est déjà utilisé sur le site');
                }                    
            } else {
                unset($homomail);
            }
        }

        if (!(isset($homomail) || isset($homonyme))) {
            $form = $this->createForm(new completeProfilType(), $nana);
            $form->bind($request);
            if ($form->isValid()) {
                $p = $nana->getPassword();
                if (empty($p)) {
                    $nana->setPassword($ancienPassword);
                } else {
                    $factory = $this->get('security.encoder_factory');
                    $encoder = $factory->getEncoder($nana);
                    $password = $encoder->encodePassword($nana->getPassword(), $nana->getSalt());
                    $nana->setPassword($password);
                }

                // Mise à jour des points pour les escarpins
                $points = $this->container->getParameter('action_points');
                $_points = 0;
                if ($this->isProfileComplete($nana)) {
                    if (!$ancienProfilComplet) {
                        $_points += $points['completer_profil'];
                    }
                } else {
                    if ($ancienProfilComplet) {
                        $_points -= $points['completer_profil'];
                    }
                }
                if ($nana->getNewsletter()) {
                    if (!$ancienNewsletter) {
                        $_points += $points['abonnee_newsletter'];
                    }
                } else {
                    if ($ancienNewsletter) {
                        $_points -= $points['abonnee_newsletter'];
                    }
                }
                if ($nana->getOffresPartenaires()) {
                    if (!$ancienOffresPartenaires) {
                        $_points += $points['offres_partenaires'];
                    }
                } else {
                    if ($ancienOffresPartenaires) {
                        $_points -= $points['offres_partenaires'];
                    }
                }

                $nana->updatePopularite($_points);

                $em->flush();
                $this->get('session')->getFlashBag()->add('success', 'Les modifications ont bien été prises en compte.');
            } else {
                $errs = "";
                foreach ($form->getErrors() as $key => $error) {
                    $errs .= $error->getMessage().", ";
                }
                $this->get('session')->getFlashBag()->add('fail', 'Une erreur a empêché de sauvegarder les modifications de ton profil : '.$errs);
                
            }            
        }

        // $this->get('session')->getFlashBag()->add('fail', $form->getErrors());
        return $this->redirect($this->generateURL('Nana_myIdentite'));
    }

    /**
    *
    * myAvatarAction
    *
    * Contrôleur gérant la section 'Avatar' du profil
    *
    * @version 0.0.1
    *
    * @param string $action — mode d'action : afficher|mofidier
    *
    * @return Response
    *
    */
    public function myAvatarAction ($action) {

        // Récupération de l'entity manager qui va nous permettre de gérer les entités.
        $em = $this->get('doctrine.orm.entity_manager');      
        $rep_nana = $repository = $em->getRepository('TDN\Bundle\NanaBundle\Entity\Nana');
        $rep_journal = $repository = $em->getRepository('TDN\Bundle\CoreBundle\Entity\Journal');
        
        $variables = array();  
        $vars['me'] = $this->container->get('security.context')->getToken()->getUser();

        if ($action === 'modifier') {
            return $this->_updateMyAvatarAction($vars);
        } else {
            return $this->_showMyAvatarAction($vars);
        }

    }

    /**
    *
    * _showMyIdentiteAction
    *
    * Préparation pour l'affichage du formulaire 'Avatar'
    *
    * @version 1.1.1
    *
    * @param array $vars — Données pour l'affichage
    *
    * @return Response
    *
    **/
    private function _showMyAvatarAction (array $vars) {

        // Formulaire pour changer d'avatar
        $vars['form_avatar'] = $this->createForm(new simpleImageType(), new Image)->createView();

        return $this->render('TDNNanaBundle:Partiels:myProfil_Avatar.html.twig', $vars);
    }

    /**
    *
    * _updateMyIdentiteAction
    *
    * Traitement du formulaire d'identité
    *
    * @version 1.1.1
    *
    * @param array $vars — Données pour l'affichage
    *
    * @return Response
    *
    **/
    private function _updateMyAvatarAction (array $vars) {

        $request = $this->get('request');

        $variables['rubrique'] = 'tdn';

       // Récupération de l'entity manager qui va nous permettre de gérer les entités.
        $em = $this->get('doctrine.orm.entity_manager');
        $imageProcessor = $this->get('tdn.image_processor');
        $usr= $this->get('security.context')->getToken()->getUser();
    
        // Le profil était-il déjà complet
        // $ancienProfilComplet = $this->isProfileComplete($usr);
       // Formulaire pour changer d'avatar
        $avatar = new Image;
        $form_avatar = $this->createForm(new simpleImageType(), $avatar);

        $form_avatar->handleRequest($request);

        echo $request->request->get('titre');

        if ($form_avatar->isValid()) {      
            // Création du nouvel avatar
            $now = new \DateTime;
            echo phpversion(); die;
            $dossier = '/profils/'.$usr->getIdNana().'/';
            $avatar->init($usr, $dossier);
            $usr->setLnAvatar($avatar);
            // Post-traitement de l'image
            $avatar = $usr->getLnAvatar()->getFichier();
            $source = $this->container->getParameter('media_root').$dossier.$avatar;
            $err = $imageProcessor->square($source, 80, 'min_');
            $err = $imageProcessor->square($source, 300, 'sqr_');
            $err = $imageProcessor->downScale($source, 700, 'height');

            $points = $this->container->getParameter('action_points');
            if ($this->isProfileComplete($usr) && !$ancienProfilComplet) {
                // $usr->updatePopularite($points['completer_profil']);
            }

            // $em->flush();
            return $this->redirect($this->generateURL('NanaBundle_myProfil'));
        }

        $vars['form_avatar'] = $form_avatar->createView();
        // print_r($form->getErrors()); die;
        return $this->render('TDNNanaBundle:Partiels:myProfil_Avatar.html.twig', $vars);
    }



}
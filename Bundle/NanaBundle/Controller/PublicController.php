<?php

namespace TDN\Bundle\NanaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;


use TDN\Bundle\NanaBundle\Form\Model\Inscription;
use TDN\Bundle\NanaBundle\Form\Type\InscriptionType;
use TDN\Bundle\NanaBundle\Form\Type\shortRegisterType;
use TDN\Bundle\NanaBundle\Entity\Nana;
use TDN\Bundle\NanaBundle\Entity\NanaRoles;
use TDN\Bundle\NanaBundle\Entity\NanaPortraitImageProxy;
use TDN\Bundle\NanaBundle\Form\Type\completeProfilType;
use TDN\Bundle\NanaBundle\Entity\NanaHobby;
use TDN\Bundle\NanaBundle\Form\Type\HobbyType;

use TDN\Bundle\CoreBundle\Entity\Journal;

use TDN\Bundle\ImageBundle\Form\Type\simpleImageType;
use TDN\Bundle\ImageBundle\Entity\Image;

class PublicController extends Controller {

    private function authenticateUser($user) {

        $providerKey = 'general'; // your firewall name
        $token = new UsernamePasswordToken($user, null, $providerKey, $user->getRoles());
        $this->container->get('security.context')->setToken($token);
    }

    protected function upgradePopularite ($action, $who = NULL) {

        $em = $this->get('doctrine.orm.entity_manager');      

        if (is_null($who)) {
            $who = $this->get('security.context')->getToken()->getUser();
        }
        if ($who instanceof Nana) {
            $backGrade = $who->getGrade();
            $points = $this->container->getParameter('action_points');
            $who->updatePopularite($points[$action]);
            $newGrade = $who->getGrade();
            if ($newGrade > $backGrade) {
                // Signaler dans le journal
                $entree = new Journal;
                $entree->setLnActeur($who);
                $entree->setAction('UPGRADE');
                $route = 'NanaBundle_profil';
                $params = array('id' => $who->getIdNana());
                $entree->setURL($this->generateURL($route, $params));
                $entree->setTexte('a obtenu un nouvel escarpin');
                $entree->setTitre('');
                $entree->setSupport('');
                $entree->setLnRubrique(NULL);
                $entree->setDateEntree(new \DateTime);
                $entree->setLnVeilleur($who);
                $em->persist($entree);
            }
            return $newGrade;
        }
        return false;
    }

    public function showMyProfilAction () {

        // Récupération de l'entity manager qui va nous permettre de gérer les entités.
        $em = $this->get('doctrine.orm.entity_manager');      
        $rep_nana = $repository = $em->getRepository('TDN\Bundle\NanaBundle\Entity\Nana');
        $rep_journal = $repository = $em->getRepository('TDN\Bundle\CoreBundle\Entity\Journal');
        $whoami = $this->container->get('security.context')->getToken()->getUser();

        $request = $this->get('request');
        $isUpgradeFlag = $request->query->get('msg');
         if ($isUpgradeFlag === 'upesc') {
            $entrees = $rep_journal->findBy(array('action' => 'UPGRADE', 'lnVeilleur' => $whoami->getIdNana()));
            if (!empty($entrees)) {
                foreach($entrees as $e) {
                    $e->setLnVeilleur(NULL);
                }
            }
        }
        $em->flush();
        
        $variables = array();  
        $vars['rubrique'] = "tdn";

        // Extraction de l'utilisateur courant
        // $usr= $this->get('security.context')->getToken()->getUser();
        // ou même $this->getUser() ?

        $vars['me'] = $whoami;
        $id = $whoami->getIdNana();
        // $vars['countHobbies'] = $vars['me']->getHobbies()->count();
        $vars['countGaleriePerso'] = $vars['me']->getGaleriePerso()->count();
        $vars['my_followers'] = $vars['me']->getIsFollowed();
        $vars['my_hobbies'] = $vars['me']->getLnHobbies();
        $vars['countHobbies'] = count($vars['my_hobbies']);
        // Instanciation du Repository
        // $repository = $em->getRepository('TDNRedactionBundle:Article');
        
        $vars['productions'] = array();
        $rep_doc = $em->getRepository('TDN\Bundle\RedactionBundle\Entity\Article');
         $vars['productions']['articles'] = $rep_doc->findBy(
            array('lnAuteur' => $vars['me']->getIdNana()),
            array('datePublication' => 'DESC'),
            20,0
        );
        $rep_doc = $em->getRepository('TDN\Bundle\ConseilExpertBundle\Entity\ConseilExpert');
        $vars['productions']['demandes'] = $rep_doc->findBy(
            array('lnAuteur' => $vars['me']->getIdNana()),
            array('datePublication' => 'DESC'),
            20,0
        );
        $rep_doc = $em->getRepository('TDN\Bundle\ConseilExpertBundle\Entity\ConseilExpert');
        $vars['productions']['conseils'] = $rep_doc->findBy(
            array('lnExpert' => $vars['me']->getIdNana()),
            array('datePublication' => 'DESC'),
            20,0
        );
        $rep_doc = $em->getRepository('TDN\Bundle\CauseuseBundle\Entity\Question');
        $vars['productions']['questions'] = $rep_doc->findBy(
            array('lnAuteur' => $vars['me']->getIdNana()),
            array('datePublication' => 'DESC'),
            20,0
        );
        $rep_doc = $em->getRepository('TDN\Bundle\CauseuseBundle\Entity\Reponse');
        $vars['productions']['reponses'] = $rep_doc->findBy(
            array('lnAuteur' => $vars['me']->getIdNana()),
            array('datePublication' => 'DESC'),
            20,0
        );
        $rep_doc = $em->getRepository('TDN\Bundle\CommentaireBundle\Entity\Commentaire');
        $vars['productions']['commentaires'] = $rep_doc->findBy(
            array('filAuteur' => $vars['me']->getIdNana()),
            array('datePublication' => 'DESC'),
            20,0
        );

        // foreach ($vars['me']->getFilProductions() as $p) {
        //     $classe = explode('\\', get_class($p));
        //     $nomClasse = array_pop($classe);
        //     $vars['my_'.strtolower($nomClasse)][] = $p;
        //     $vars['productions'] += 1;
        //      }

        $vars['likes'] = NULL;

       // Formulaire pour les données personnelles
        $vars['form'] = $this->createForm(new completeProfilType(), $vars['me'])->createView();
        // Formulaire pour changer d'avatar
        $vars['form_avatar'] = $this->createForm(new simpleImageType(), new Image)->createView();
        // Formulaire pour la galerie d'images
        $vars['form_galerie'] = $this->createForm(new simpleImageType(), new Image)->createView();
        // Formulaire pour les hobbies
        $vars['form_hobby'] = $this->createForm(new HobbyType(), new NanaHobby)->createView();
        $vars['form_image_hobby'] = $this->createForm(new simpleImageType(), new Image)->createView();
        $vars['form_modification_hobby'] = $this->createForm(new HobbyType(), new NanaHobby)->createView();

        $vars['likes'] = $rep_journal->likes($vars['me']->getIdNana());

        return $this->render('NanaBundle:Public:selfProfil.html.twig', $vars);
    }

    public function showProfilAction ($id) {

        $variables = array();  
        $vars['rubrique'] = "tdn";

        $whoami = $this->container->get('security.context')->getToken()->getUser();
        // Récupération de l'entity manager qui va nous permettre de gérer les entités.
        $em = $this->get('doctrine.orm.entity_manager');      
        $rep_nana = $repository = $em->getRepository('TDN\Bundle\NanaBundle\Entity\Nana');
        $rep_journal = $repository = $em->getRepository('TDN\Bundle\CoreBundle\Entity\Journal');
        $vars['me'] = $rep_nana->find($id);

        if ($vars['me']->getActive() == 0) {
           return $this->render('NanaBundle:Public:inactiveProfil.html.twig', $vars);
        } else {
            // $vars['countHobbies'] = $vars['me']->getHobbies()->count();
            $vars['countGaleriePerso'] = $vars['me']->getGaleriePerso()->count();
            $vars['my_hobbies'] = $vars['me']->getLnHobbies();
            $vars['countHobbies'] = count($vars['my_hobbies']);

            $vars['productions'] = array();
            $rep_doc = $em->getRepository('TDN\Bundle\RedactionBundle\Entity\Article');
             $vars['productions']['articles'] = $rep_doc->findBy(
                array('lnAuteur' => $vars['me']->getIdNana(),
                      'statut' => 'ARTICLE_PUBLIE'),
                array('datePublication' => 'DESC'),
                20,0
            );
            $rep_doc = $em->getRepository('TDN\Bundle\ConseilExpertBundle\Entity\ConseilExpert');
            $vars['productions']['demandes'] = $rep_doc->findBy(
                array('lnAuteur' => $vars['me']->getIdNana(),
                      'statut' => 'CONSEIL_PUBLIE'),
                array('datePublication' => 'DESC'),
                20,0
            );
            $rep_doc = $em->getRepository('TDN\Bundle\ConseilExpertBundle\Entity\ConseilExpert');
            $vars['productions']['conseils'] = $rep_doc->findBy(
                array('lnExpert' => $vars['me']->getIdNana(),
                      'statut' => 'CONSEIL_PUBLIE'),
                array('datePublication' => 'DESC'),
                20,0
            );
            $rep_doc = $em->getRepository('TDN\Bundle\CauseuseBundle\Entity\Question');
            $vars['productions']['questions'] = $rep_doc->findBy(
                array('lnAuteur' => $vars['me']->getIdNana(),
                      'statut' => 'QUESTION_PUBLIEE'),
                array('datePublication' => 'DESC'),
                20,0
            );
            $rep_doc = $em->getRepository('TDN\Bundle\CauseuseBundle\Entity\Reponse');
            $vars['productions']['reponses'] = $rep_doc->findBy(
                array('lnAuteur' => $vars['me']->getIdNana(),
                      'statut' => 'REPONSE_PUBLIEE'),
                array('datePublication' => 'DESC'),
                20,0
            );
            $rep_doc = $em->getRepository('TDN\Bundle\CommentaireBundle\Entity\Commentaire');
            $vars['productions']['commentaires'] = $rep_doc->findBy(
                array('filAuteur' => $vars['me']->getIdNana()),
                array('datePublication' => 'DESC'),
                20,0
            );

            $vars['likes'] = $rep_journal->likes($vars['me']->getIdNana());

            // $x = $whoami->getIdNana();
            // if ($x == 1) {
            //     print_r(count($follows));
            //     foreach($follows as $f) print_r($f->getIdNana());
            // }

            if ($whoami instanceof Nana) {
                $follows = $whoami->getFollows();
                $vars['alreadyFollowed'] = in_array($vars['me'], $follows->toArray());
            } else {
                $vars['alreadyFollowed'] = false;
            }

            return $this->render('TDNNanaBundle:Pages:completeProfil.html.twig', $vars);
        }
    }

    /**
    *
    * showProfilIdentiteAction
    *
    * Contrôleur pour l'affichage des données personnelles d'une Nana
    *
    * @version 1.1
    *
    * @param integer $id — Identifiant de la nana
    *
    * @return Response
    *
    **/
    public function showProfilIdentiteAction ($id) {

        $variables = array();  
        $vars['rubrique'] = "tdn";

        $whoami = $this->container->get('security.context')->getToken()->getUser();
        // Récupération de l'entity manager qui va nous permettre de gérer les entités.
        $em = $this->get('doctrine.orm.entity_manager');      
        $rep_nana = $repository = $em->getRepository('TDN\Bundle\NanaBundle\Entity\Nana');
        $rep_journal = $repository = $em->getRepository('TDN\Bundle\CoreBundle\Entity\Journal');
        $vars['me'] = $rep_nana->find($id);

        if ($vars['me']->getActive() == 0) {
           return $this->render('NanaBundle:Public:inactiveProfil.html.twig', $vars);
        } else {
            return $this->render('TDNNanaBundle:Partiels:identiteNana.html.twig', $vars);
        }
    }

    /**
    *
    * showProfilGaleriesAction
    *
    * Contrôleur pour l'affichage des images et du réseau d'une Nana
    *
    * @version 1.1
    *
    * @param integer $id — Identifiant de la nana
    *
    * @return Response
    *
    **/
    public function showProfilGaleriesAction ($id) {

        $variables = array();  
        $vars['rubrique'] = "tdn";

        $whoami = $this->container->get('security.context')->getToken()->getUser();
        // Récupération de l'entity manager qui va nous permettre de gérer les entités.
        $em = $this->get('doctrine.orm.entity_manager');      
        $rep_nana = $repository = $em->getRepository('TDN\Bundle\NanaBundle\Entity\Nana');
        $rep_journal = $repository = $em->getRepository('TDN\Bundle\CoreBundle\Entity\Journal');
        $vars['me'] = $rep_nana->find($id);

        if ($vars['me']->getActive() == 0) {
           return $this->render('NanaBundle:Public:inactiveProfil.html.twig', $vars);
        } else {
            // $vars['countHobbies'] = $vars['me']->getHobbies()->count();
            $vars['countGaleriePerso'] = $vars['me']->getGaleriePerso()->count();
            $vars['my_hobbies'] = $vars['me']->getLnHobbies();
            $vars['countHobbies'] = count($vars['my_hobbies']);

            if ($whoami instanceof Nana) {
                $follows = $whoami->getFollows();
                $vars['alreadyFollowed'] = in_array($vars['me'], $follows->toArray());
            } else {
                $vars['alreadyFollowed'] = false;
            }

            return $this->render('TDNNanaBundle:Partiels:galeriesNana.html.twig', $vars);
        }
    }

    /**
    *
    * showProfilActivite
    *
    * Contrôleur pour l'affichage de l'activité d'une Nana
    *
    * @version 1.1
    *
    * @param integer $id — Identifiant de la nana
    *
    * @return Response
    *
    **/
    public function showProfilActiviteAction ($id) {

        $variables = array();  
        $vars['rubrique'] = "tdn";

        $whoami = $this->container->get('security.context')->getToken()->getUser();
        // Récupération de l'entity manager qui va nous permettre de gérer les entités.
        $em = $this->get('doctrine.orm.entity_manager');      
        $rep_nana = $repository = $em->getRepository('TDN\Bundle\NanaBundle\Entity\Nana');
        $rep_journal = $repository = $em->getRepository('TDN\Bundle\CoreBundle\Entity\Journal');
        $vars['me'] = $rep_nana->find($id);

        if ($vars['me']->getActive() == 0) {
           return $this->render('NanaBundle:Public:inactiveProfil.html.twig', $vars);
        } else {
            $vars['productions'] = array();
            $rep_doc = $em->getRepository('TDN\Bundle\RedactionBundle\Entity\Article');
             $vars['productions']['articles'] = $rep_doc->findBy(
                array('lnAuteur' => $vars['me']->getIdNana(),
                      'statut' => 'ARTICLE_PUBLIE'),
                array('datePublication' => 'DESC'),
                20,0
            );
            $rep_doc = $em->getRepository('TDN\Bundle\ConseilExpertBundle\Entity\ConseilExpert');
            $vars['productions']['demandes'] = $rep_doc->findBy(
                array('lnAuteur' => $vars['me']->getIdNana(),
                      'statut' => 'CONSEIL_PUBLIE'),
                array('datePublication' => 'DESC'),
                20,0
            );
            $rep_doc = $em->getRepository('TDN\Bundle\ConseilExpertBundle\Entity\ConseilExpert');
            $vars['productions']['conseils'] = $rep_doc->findBy(
                array('lnExpert' => $vars['me']->getIdNana(),
                      'statut' => 'CONSEIL_PUBLIE'),
                array('datePublication' => 'DESC'),
                20,0
            );
            $rep_doc = $em->getRepository('TDN\Bundle\CauseuseBundle\Entity\Question');
            $vars['productions']['questions'] = $rep_doc->findBy(
                array('lnAuteur' => $vars['me']->getIdNana(),
                      'statut' => 'QUESTION_PUBLIEE'),
                array('datePublication' => 'DESC'),
                20,0
            );
            $rep_doc = $em->getRepository('TDN\Bundle\CauseuseBundle\Entity\Reponse');
            $vars['productions']['reponses'] = $rep_doc->findBy(
                array('lnAuteur' => $vars['me']->getIdNana(),
                      'statut' => 'REPONSE_PUBLIEE'),
                array('datePublication' => 'DESC'),
                20,0
            );
            $rep_doc = $em->getRepository('TDN\Bundle\CommentaireBundle\Entity\Commentaire');
            $vars['productions']['commentaires'] = $rep_doc->findBy(
                array('filAuteur' => $vars['me']->getIdNana()),
                array('datePublication' => 'DESC'),
                20,0
            );

            $vars['likes'] = $rep_journal->likes($vars['me']->getIdNana());

            return $this->render('TDNNanaBundle:Partiels:activiteNana.html.twig', $vars);
        }
    }

    public function registerAction() {

        $form = $this->createForm(new InscriptionType(), new Inscription());
        $variables['form'] = $form->createView();
        $variables['message'] = "";

        return $this->render('NanaBundle:Public:register.html.twig', $variables);
    }

    public function registerConcoursAction() {

        $form = $this->createForm(new InscriptionType(), new Inscription());
        $variables['form'] = $form->createView();
        $variables['message'] = "";

        return $this->render('NanaBundle:Public:registerConcours.html.twig', $variables);
    }

    public function registerCheckAction()	{
	    
        $request = $this->get('request');
       // Récupération de l'entity manager qui va nous permettre de gérer les entités.
        $em = $this->get('doctrine.orm.entity_manager');
        $session = $this->container->get('session');
        $factory = $this->container->get('security.encoder_factory');
        $URLmanager = $this->get('tdn.document.url');

        $form = $this->createForm(new InscriptionType(), new Inscription);
        $form->bind($request);
        // L'inscription suit-elle un abonnement à la newsletter ?
        $pathNewsletter = $request->get('nslFollowing');
        if ($pathNewsletter == 1) {
            $referer = $request->get('referer');
            $callback = $request->get('callback');
            $_isValide = ($callback = base64_encode($referer));
        } else {
            $_isValide = $form->isValid();
        }
        if ($_isValide) {
            // Recherche de rôle
            $registration = $form->getData();
            $nana = $form->getData()->getUser();
            $rep_nana = $repository = $em->getRepository('TDN\Bundle\NanaBundle\Entity\Nana');
            $doublon = $rep_nana->findDoubleRegistration($nana->getUsername(), $nana->getEmail());
            $rep_roles = $em->getRepository('TDN\Bundle\NanaBundle\Entity\NanaRoles');
            $role = $rep_roles->find('ROLE_USER');
            if ($doublon) {
                    $this->get('session')->getFlashBag()->add('fail', 'Un profil existe déjà pour ce pseudo ou cette adresse électronique');
            } else {
                $nana->addRole($role);
                $nakedPassword = $nana->getPassword();
                $nana->setSalt(uniqid());
                $encoder = $factory->getEncoder($nana);
                $pwd = $encoder->encodePassword($nakedPassword, $nana->getSalt());
                $nana->setPassword($pwd);
                $nana->setDateInscription(new \DateTime);
                // Valeurs par defaut
                if ($pathNewsletter == 1) {
                    $nana->setNewsletter(1);
                } else {
                    $nana->setNewsletter($nana->getOffresPartenaires());
                }
                $nana->setBlacklist(0);
                $nana->setActive(1);
                $nana->resetPopularite();
                $points = $this->container->getParameter('action_points');
                $level = $points['inscription'];
                $nana->updatePopularite($level);
                // Enregistrment
                $em->persist($nana);
                $em->flush();

                // Notifier la nouvelle Nana
                $admins = $this->container->getParameter('admin_notifications');
                $expediteurs = $this->container->getParameter('mail_expediteur');               
                $message = \Swift_Message::newInstance();
                $corps['expediteur'] = "Justine";
                $corps['role'] = "Rédaction";
                $corps['destinataire'] = $nana->getUsername();
                $corps['dateEnvoi'] = date(' d m Y - H:i:s');

                $message->setSubject('[TDN] Inscription sur TDN')
                        ->setContentType('text/html')
                        ->setFrom($expediteurs['redaction'])
                        ->setTo($nana->getEmail())
                        ->setBody(
                            $this->renderView('NanaBundle:Mail:inscription.html.twig', $corps),
                            'text/html'
                );
                foreach($admins['redaction'] as $destinataire) {
                    $message->addBcc($destinataire);
                }
                $this->get('mailer')->send($message);

                $this->authenticateUser($nana);
                // $key = '_security.general.target_path';
                // if ($session->has($key)) {
                //     $url = $session->get($key);
                //     $session->remove($key);
                // } else {
                //     $url = $this->container->get('router')->generate('homepage');
                // }

                // $this->get('session')->getFlashBag()->add('success', 'Merci. Ton compte est créé, tu peux te connecter');
                // return new RedirectResponse($url);
            }

            // Suppression de la liste anonyme des abonnements à la newletter
            // si la paersonne a enchaîné avec une inscription
            if ($pathNewsletter == 1) {
                $rep_abonnes = $repository = $em->getRepository('TDN\Bundle\NewsletterBundle\Entity\AbonneNewsletter');
                $abonne = $rep_abonnes->find($nana->getEmail());
                $em->remove($abonne);
            }
    	} else {
            $errors = array();
             foreach ($form->getErrors() as $key => $error) {
                $errors[] = $error->getMessage();
            } 
            print_r($errors);
            // $report = implode(', ', $errors);
            // $this->get('session')->getFlashBag()->add('fail', 'Tu as fait une erreur dans tes données d’inscription : '.$report);
         }

        if ($pathNewsletter == 1) {
            return $this->redirect($request->get('referer'));                
        } else {
            return $this->redirect($URLmanager->refererURL($request->headers->get('referer')));
        }
	}

    private function isProfileComplete ($nana) {
        $prenom = $nana->getPrenom();
        $nom = $nana->getNom();
        $bio = $nana->getBiographie();
        $avatar = $nana->getLnAvatar();

        return !(empty($prenom) || empty($nom) || empty($bio) || empty($avatar));
    }

    public function updateProfilAction () {

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
        return $this->redirect($this->generateURL('NanaBundle_myProfil'));
    }

    public function updateAvatarAction () {

        $request = $this->get('request');

        $variables['rubrique'] = 'tdn';

       // Récupération de l'entity manager qui va nous permettre de gérer les entités.
        $em = $this->get('doctrine.orm.entity_manager');
        $imageProcessor = $this->get('tdn.image_processor');
        $rep_nana = $em->getRepository('TDN\Bundle\NanaBundle\Entity\Nana');
        $usr= $this->get('security.context')->getToken()->getUser();
    
        // Le profil était-il déjà complet
        $ancienProfilComplet = $this->isProfileComplete($usr);
       // Formulaire pour changer d'avatar
        $avatar = new Image;
        $form_avatar = $this->createForm(new simpleImageType(), $avatar);
        $form_avatar->bind($request);

        if ($form_avatar->isValid()) {      
            // Création du nouvel avatar
            $now = new \DateTime;
            $dossier = '/profils/'.$usr->getIdNana().'/';
            $avatar->init($dossier, $usr);
            // Mise à jour du profil
            $usr->setLnAvatar($avatar);

            $em->flush();

            // Post-traitement de l'image
            $avatar = $usr->getLnAvatar()->getFichier();
            $source = $this->container->getParameter('media_root').$dossier.$avatar;
            $err = $imageProcessor->square($source, 300, 'sqr_');
            $err = $imageProcessor->downScale($source, 700, 'height');

            $points = $this->container->getParameter('action_points');
            if ($this->isProfileComplete($usr) && !$ancienProfilComplet) {
                $usr->updatePopularite($points['completer_profil']);
            }
        }

        // print_r($form->getErrors()); die;

        return $this->redirect($this->generateURL('NanaBundle_myProfil'));
    }

    public function updateGalerieAction () {

        $request = $this->get('request');

        $variables['rubrique'] = 'tdn';

       // Récupération de l'entity manager qui va nous permettre de gérer les entités.
        $em = $this->get('doctrine.orm.entity_manager');
        $rep_nana = $em->getRepository('TDN\Bundle\NanaBundle\Entity\Nana');
        $usr= $this->get('security.context')->getToken()->getUser();
    
       // Formulaire pour changer d'avatar
        $photo = new Image;
        $form_galerie = $this->createForm(new simpleImageType(), $photo);
        $form_galerie->bind($request);

        if ($form_galerie->isValid()) {      
            // Enregistrement d'une nouvelle photo
            $now = new \DateTime;
            $dossier = '/profils/'.$usr->getIdNana().'/';
            $photo->init($dossier, $usr);

            // Mise à jour du profil
            $proxy = new NanaPortraitImageProxy;
            $proxy->setLnImage($photo);
            $proxy->setLnPortrait($usr);
            $proxy->setIsAvatar(0);
            // $nana->addGaleriePerso($proxy);

            // Post-traitement de l'image
            $imageProcessor = $this->get('tdn.image_processor');
            $fichierImage = $photo->getFichier();
            $source = $this->container->getParameter('media_root').$dossier.$fichierImage;
            $err = $imageProcessor->square($source, 300, 'sqr_');
            $err = $imageProcessor->downScale($source, 700, 'height');

            // $points = $this->container->getParameter('action_points');
            // $usr->updatePopularite($points['completer_profil']);

            $em->persist($proxy);
            $em->flush();


        }

        // print_r($form->getErrors()); die;

        return $this->redirect($this->generateURL('NanaBundle_myProfil'));
    }

    function supprimerElementGalerieAction ($id) {

        $request = $this->get('request');

        $variables['rubrique'] = 'tdn';

       // Récupération de l'entity manager qui va nous permettre de gérer les entités.
        $em = $this->get('doctrine.orm.entity_manager');
        $rep_nana = $em->getRepository('TDN\Bundle\NanaBundle\Entity\Nana');
        $usr= $this->get('security.context')->getToken()->getUser();

        $galerie = $usr->getGaleriePerso();
        foreach ($galerie as $g) {
            if ($g->getIdPortrait() == $id) {
                $image = $g->getLnImage();
                $usr->removeGaleriePerso($g);
                $em->remove($g);
                $em->remove($image);
            }
        }

        $em->flush();

        $this->get('session')->getFlashBag()->add('success', 'La photo <strong>'.$image->getFichier().'</strong> a été supprimée');
        return $this->redirect($this->generateURL('NanaBundle_myProfil'));
    }

    public function followAction ($nana) {

        $request = $this->get('request');

        // Récupération de l'entity manager qui va nous permettre de gérer les entités.
        $em = $this->get('doctrine.orm.entity_manager');      
        $whoami = $this->container->get('security.context')->getToken()->getUser();
        $rep_nana = $repository = $em->getRepository('TDN\Bundle\NanaBundle\Entity\Nana');
        $target = $rep_nana->find($nana);

        $followed = $whoami->getFollows();

        if ($followed->contains($target)) {
            // echo 'Déjà suivi'; die;
           $this->get('session')->getFlashBag()->add('warning', 'Tu suis déjà les activités de <strong>'.$target->getUsername().'</strong>');
        } else {
            // echo 'Pas vu'; die;
            $whoami->addFollow($target);

            // Gratification
            $points = $this->container->getParameter('action_points');
            $grade = $this->upgradePopularite('suivre_nana', $whoami);
            $grade = $this->upgradePopularite('nana_suivie', $target);

            // Signaler dans le journal & informer l'auteur
            // MOD 13-09-2013
            $entree = new Journal;
            if ($whoami instanceof Nana ) $entree->setLnActeur($whoami);
            $entree->setAction('SUIT');
            $entree->setURL($this->generateURL('NanaBundle_profil', array('id' => $target->getIdNana())));
            $entree->setTexte('suit maintenant');
            $entree->setTitre($target->getUsername());
            $entree->setLnVeilleur($target);
            $entree->setSupport('');
            $rep_rubrique = $em->getRepository('TDN\Bundle\DocumentBundle\Entity\DocumentRubrique');
            $rubrique = $rep_rubrique->find(8);
            $entree->setLnRubrique($rubrique);
            $entree->setDateEntree(new \DateTime);
            // $em->persist($entree);
            // ENDMOD

            $em->flush();

           $this->get('session')->getFlashBag()->add('success', 'Tu suis maintenant les activités de <strong>'.$target->getUsername().'</strong>');
            
        }

       return $this->redirect($this->generateURL('NanaBundle_profil', array('id' => $nana)));
    }

    public function unfollowAction ($nana) {

        $request = $this->get('request');

        // Récupération de l'entity manager qui va nous permettre de gérer les entités.
        $em = $this->get('doctrine.orm.entity_manager');      
        $whoami = $this->container->get('security.context')->getToken()->getUser();
        $rep_nana = $repository = $em->getRepository('TDN\Bundle\NanaBundle\Entity\Nana');
        $target = $rep_nana->find($nana);

        $whoami->addFollow($target);

        $em->flush();

       $this->get('session')->getFlashBag()->add('success', 'Tu suis maintenant les activités de <strong>'.$nana->getUsername().'</strong>');
       return $this->redirect($this->generateURL('NanaBundle_profil', array('id' => $nana)));

    }

    public function rechercheNanasAction () {

        $request = $this->get('request');
        if ($request->isMethod('POST')) {
            $seed = $request->get('seed');
        } else {
            $seed = $request->query->get('seed');
        }

        if (strlen($seed) > 2) {
            // Récupération de l'entity manager qui va nous permettre de gérer les entités.
            $em = $this->get('doctrine.orm.entity_manager');      
            $rep_nana = $repository = $em->getRepository('TDN\Bundle\NanaBundle\Entity\Nana');
            $vars['reponses'] = $rep_nana->findBySeed($seed);            
        } else {
            $this->get('session')->getFlashBag()->add('fail', 'Le moteur de recherche exige au moins trois caractères');
            $vars['reponses'] = false;
        }

        $vars['rubrique'] = 'tdn';
        return $this->render('NanaBundle:Public:resultatsRecherche.html.twig', $vars);
    }

    public function messagePriveAction ($id = NULL) {

        $request = $this->get('request');
        $whoami = $this->container->get('security.context')->getToken()->getUser();
        $em = $this->get('doctrine.orm.entity_manager');      
        $rep_nana = $repository = $em->getRepository('TDN\Bundle\NanaBundle\Entity\Nana');
         
        if ($request->isMethod('POST') && ($whoami instanceof Nana)) {
            $target = $rep_nana->find($request->get('destinataire'));
            $expediteur = filter_var($whoami->getEmail(), FILTER_VALIDATE_EMAIL);
            if ($expediteur !== false) {
                $message = \Swift_Message::newInstance();
                $corps['expediteur'] = $whoami->getUsername() ;
                $corps['role'] = "Nana";
                $corps['destinataire'] = $target->getUsername();
                $corps['dateEnvoi'] = date(' d m Y - H:i:s');
                $corps['pseudo'] = $whoami->getUsername();
                $corps['message'] = $request->get('message');

                $message->setSubject('[Trucdenana] Message privé de : '.$whoami->getUsername())
                        ->setContentType('text/html')
                        ->setFrom($whoami->getEmail())
                        ->setTo($target->getEmail())
                        ->setBcc($this->container->getParameter('mail_moderation_1'))
                        ->setBody(
                            $this->renderView('NanaBundle:Mail:message.html.twig', $corps),
                            'text/html'
                        );
                $this->get('mailer')->send($message);
     
                $this->get('session')->getFlashBag()->add('success', 'Ton message a bien été envoyé à <strong>'.$target->getUsername().'</strong>');                
            }
            return $this->redirect($this->generateURL('NanaBundle_profil', array('id' => $target->getIdNana())));

        }

        $target = $rep_nana->find($id);
        $vars['idDestinataire'] = $id;
        return $this->render('NanaBundle:Bloc:messagePrive.html.twig', $vars);
       
    }

        public function partageAction ($id = NULL) {

        $request = $this->get('request');
        $session = $this->get('session');
        $security = $this->container->get('security.context');
        $whoami = $security->getToken()->getUser();
        $em = $this->get('doctrine.orm.entity_manager');      
        $URLmanager = $this->get('tdn.document.url');

        $rep_doc = $em->getRepository('TDN\Bundle\DocumentBundle\Entity\Document');
     
        if ($request->isMethod('POST')) {
            $target = $request->get('destinataire');
            
            foreach($target as $targetEmail) {
                // Notification
                $message = \Swift_Message::newInstance();
                if ($whoami instanceof Nana) {
                    $exp = $whoami->getPrenom();
                    $expMail = $whoami->getEmail();
                    $expPseudo = $whoami->getUsername();
                } else {
                    $exp = "Un(e) ami(e)";
                    $expMail = $request->get('expediteur');
                    $expPseudo = "";
                }
                
                if (filter_var($expMail, FILTER_VALIDATE_EMAIL)) {
                    // Elements génériques
                    $corps['expediteur'] = $expPseudo ;
                    $corps['role'] = "Nana";
                    $corps['destinataire'] = "";
                    $corps['dateEnvoi'] = date(' d m Y - H:i:s');
                    // Elements spécifiques
                    $corps['message'] = $request->get('message');
                    $corps['message'] = preg_replace('/\n/', '<br />', $corps['message']);
                    $corps['message'] .= "<p>Suis le lien : ".$session->get('url')."</p>" ;

                    $message->setSubject('[Trucdenana] '.$exp.' souhaite partager avec vous un contenu qui pourrait t’intéresser')
                            ->setContentType('text/html')
                            ->setFrom($expMail)
                            ->setBcc('michel.cadennes@sens-commun.fr')
                            ->setTo($targetEmail)
                            ->setBody(
                                $this->renderView('NanaBundle:Mail:message.html.twig', $corps),
                                'text/html'
                            );
                    $this->get('mailer')->send($message);
                }

                $session->remove('url');

                if ($security->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
                    $points = $this->container->getParameter('action_points');
                    $exposant = count($target) > 0 ? 1 : 0;
                    $score = $points['partage_contenu'] * pow(2, $exposant);
                    $whoami->updatePopularite($score);                    
                }

                $em->flush();
            }
 
            $this->get('session')->getFlashBag()->add('success', 'Ton message a bien été envoyé.');
            return $this->redirect($URLmanager->refererURL($request->headers->get('referer')));
        }

        $document = $rep_doc->find($id);
        list($route, $rubrique, $params) = $document->getURLElements();
        $url = 'http://www.trucdenana.com'.$this->generateURL($route, $params);
        $session->set('url', "<a href='$url'>".$document->getTitre()."</a>");

        return $this->render('NanaBundle:Bloc:partageDocument.html.twig');
       
    }

}

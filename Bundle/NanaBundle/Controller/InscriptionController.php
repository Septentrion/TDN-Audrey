<?php

namespace TDN\Bundle\NanaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;


use TDN\Bundle\NanaBundle\Form\Model\Inscription;
use TDN\Bundle\NanaBundle\Form\Type\InscriptionType;
use TDN\Bundle\NanaBundle\Form\Type\shortRegisterType;
use TDN\Bundle\NanaBundle\Entity\Nana;
use TDN\Bundle\NanaBundle\Entity\NanaRoles;

use TDN\Bundle\CoreBundle\Entity\Journal;

use TDN\Bundle\ImageBundle\Form\Type\simpleImageType;
use TDN\Bundle\ImageBundle\Entity\Image;

class InscriptionController extends Controller {

    public function inscriptionAction() {

        $form = $this->createForm(new InscriptionType(), new Inscription());
        $variables['form'] = $form->createView();
        $variables['message'] = "";

        return $this->render('TDNNanaBundle:Pages:inscription.html.twig', $variables);
    }

    public function inscriptionConcoursAction() {

        $form = $this->createForm(new InscriptionType(), new Inscription());
        $variables['form'] = $form->createView();
        $variables['message'] = "";

        return $this->render('NanaBundle:Pages:registerConcours.html.twig', $variables);
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
            if (!filter_var($email_a, FILTER_VALIDATE_EMAIL)) {
                    $this->get('session')->getFlashBag()->add('fail', 'L’adresse électronique n’est pas valide');
            } else {
                $rep_nana = $repository = $em->getRepository('TDN\Bundle\NanaBundle\Entity\Nana');
                $doublon = $rep_nana->findDoubleRegistration($nana->getUsername(), $nana->getEmail());
                if ($doublon) {
                        $this->get('session')->getFlashBag()->add('fail', 'Un profil existe déjà pour ce pseudo ou cette adresse électronique');
                } else {
                    $rep_roles = $em->getRepository('TDN\Bundle\NanaBundle\Entity\NanaRoles');
                    $role = $rep_roles->find('ROLE_USER');
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
                    // $em->persist($nana);
                    // $em->flush();

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
                                $this->renderView('TDNNanaBundle:Mail:inscription.html.twig', $corps),
                                'text/html'
                    );
                    foreach($admins['redaction'] as $destinataire) {
                        $message->addBcc($destinataire);
                    }
                    // $this->get('mailer')->send($message);

                    $this->authenticateUser($nana);
                }                
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
}

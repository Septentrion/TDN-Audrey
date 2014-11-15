<?php

namespace TDN\Bundle\NanaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;

use TDN\Bundle\NanaBundle\Entity\Nana;
use TDN\Bundle\NanaBundle\Form\Type\LoginType;
use TDN\Bundle\NanaBundle\Form\Type\RestaurationMotDePasseType;

class SecurityController extends Controller {

    public function loginAction () {
        $request = $this->getRequest();
        $session = $request->getSession();

        $form = $this->createForm(new LoginType(), new Nana());

        // get the login error if there is one
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(
                SecurityContext::AUTHENTICATION_ERROR
            );
        } else {
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
        }

        return $this->render('TDNNanaBundle:Security:login.html.twig',
            array(
                // last username entered by the user
                'last_username' => $session->get(SecurityContext::LAST_USERNAME),
                'error'         => $error,
                'form'          => $form->createView(),
                'rubrique'      => 'tdn',
                'redirect_url'  => $this->generateURL('NanaBundle_myProfil'),
                'redirect_params' => array()
            )
        );
    }

    public function blockLoginAction ($redirect = NULL) {
        $request = $this->getRequest();
        $session = $request->getSession();

        $form = $this->createForm(new LoginType(), new Nana());

        // get the login error if there is one
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(
                SecurityContext::AUTHENTICATION_ERROR
            );
        } else {
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
        }

        $parametres = array(
                // last username entered by the user
                'last_username' => $session->get(SecurityContext::LAST_USERNAME),
                'error'         => $error,
                'form'          => $form->createView(),
                'rubrique'      => 'tdn'
            );

        if (!is_null($redirect)) {
            $parametres['redirect_url'] = $redirect;
            $parametres['redirect_params'] = array();
        }

        return $this->render('TDNNanaBundle:Security:blocLogin.html.twig', $parametres);
    }

    public function popinLoginAction ($redirect = NULL) {
        $request = $this->getRequest();
        $session = $request->getSession();

        $form = $this->createForm(new LoginType(), new Nana());

        // get the login error if there is one
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(
                SecurityContext::AUTHENTICATION_ERROR
            );
        } else {
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
        }

        $parametres = array(
                // last username entered by the user
                'last_username' => $session->get(SecurityContext::LAST_USERNAME),
                'error'         => $error,
                'form'          => $form->createView(),
                'rubrique'      => 'tdn'
            );

        if (!is_null($redirect)) {
            $parametres['redirect_url'] = $redirect;
            $parametres['redirect_params'] = array();
        }

        return $this->render('TDNNanaBundle:Security:popinLogin.html.twig', $parametres);
    }

    public function passwordResetS1Action () {

        $vars['rubrique'] = 'tdn';

        return $this->render('TDNNanaBundle:Security:formPasswordS1.html.twig', $vars);
    }

    public function passwordResetS2Action () {

        $request = $this->getRequest();
        $_formEmail = $request->get('email');

        $em = $this->get('doctrine.orm.entity_manager');      
        $rep_nana = $repository = $em->getRepository('TDN\Bundle\NanaBundle\Entity\Nana');
        $nana = $rep_nana->findOneByEmail($_formEmail);

        if (!($nana instanceof Nana)) {
            $this->get('session')->getFlashBag()->add('fail', 'Aucun compte n’existe pour cet email');
        } else {
  
            $resetURL = $this->generateURL('Nana_passwordResetS3').'?';
            $resetURL .= "me=".$nana->getIdNana();
            $clef = sha1($nana->getPrenom()."!".$nana->getSalt());
            $resetURL .= "&clef=".$clef;

            // Notifier la nouvelle Nana
            $corps['expediteur'] = "Administrateur";
            $corps['role'] = "Système";
            $corps['destinataire'] = $nana->getUsername();
            $corps['dateEnvoi'] = date(' d m Y - H:i:s');
            $corps['resetURL'] = $resetURL;
            $corps['pseudo'] = $nana->getUsername();

            $message = \Swift_Message::newInstance()
                ->setSubject('[Trucdenana] Régénération de ton mot de passe')
                ->setFrom('postmaster@trucdenana.com')
            // ->setTo('michel.cadennes@sens-commun.fr')
                ->setTo($nana->getEmail())
                ->setBody(
                    $this->renderView('TDNNanaBundle:Mail:motDePasseOublie.html.twig', $corps),
                    'text/html'
                );
            $this->get('mailer')->send($message);
            $this->get('session')->getFlashBag()->add('success', 'Un mail t’a été envoyé pour te permettre de créer un nouveau mot de passe');
        }

        return $this->redirect($this->generateURL('Core_home'));
    }

    public function passwordResetS3Action () {

        $request = $this->getRequest();
        $_me = $request->query->get('me');
        $_clef = $request->query->get('clef');

        $em = $this->get('doctrine.orm.entity_manager');      
        $rep_nana = $repository = $em->getRepository('TDN\Bundle\NanaBundle\Entity\Nana');
        $nana = $rep_nana->find($_me);

        if (!($nana instanceof Nana)) {
            $this->get('session')->getFlashBag()->add('fail', 'Ce compte n’existe pas');
            return $this->redirect($this->generateURL('Core_home'));
        } else {
            $clef = sha1($nana->getPrenom()."!".$nana->getSalt());
            if (!($clef === $_clef)) {
                $this->get('session')->getFlashBag()->add('fail', 'Vous n’êtes pas autorisé à faire cette opération.');
                return $this->redirect($this->generateURL('Core_home'));
            } else {
                $vars['rubrique'] = 'tdn';
                $form = $this->createForm(new RestaurationMotDePasseType(), $nana);
                $vars['form'] = $form->createView();
                $vars['idNana'] = $nana->getIdNana();

                return $this->render('TDNNanaBundle:Security:formPasswordS3.html.twig', $vars);
            }
        }
    }

    public function passwordResetFinalAction () {

        $request = $this->getRequest();
        $_username = $request->get('username');
        $_idNana = $request->get('id');

        $factory = $this->container->get('security.encoder_factory');
        $em = $this->get('doctrine.orm.entity_manager');      
        $rep_nana = $repository = $em->getRepository('TDN\Bundle\NanaBundle\Entity\Nana');
        $nana = $rep_nana->find($_idNana);

        $form = $this->createForm(new RestaurationMotDePasseType(), $nana);
        $form->bind($request);

        if ($form->isValid()) {
            $nakedPassword = $nana->getPassword();
            $nana->setSalt(md5(date('Ymd')));
            $encoder = $factory->getEncoder($nana);
            $pwd = $encoder->encodePassword($nakedPassword, $nana->getSalt());
            $nana->setPassword($pwd);

            $em->flush();

            $this->get('session')->getFlashBag()->add('success', 'Ton mot de passe a été changé avec succès.');
        } else {
            $this->get('session')->getFlashBag()->add('success', 'L’opération n’est pas autorisée.');
        }
        
        return $this->redirect($this->generateURL('Core_home'));

    }
}
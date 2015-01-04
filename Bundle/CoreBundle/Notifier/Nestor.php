<?php

namespace TDN\Bundle\CoreBundle\Notifier;

use Doctrine\Common\Collections\Collection;

use TDN\Bundle\Nanabundle\Entity\Nana;

class Nestor {

  protected $mailer;
  protected $engine;
  protected $entity;
  private $corps = array('expediteur' => 'Justine', 'role' => 'redaction');
  private $metadata = array();
  private $expediteurs;
  private $notifications;
  private $squelette_template = "TDN%sBundle:Mail:%s.html.twig";

  public function __construct($mailer, $engine, $expediteurs, $notifications)
  {
    $this->mailer = $mailer;
    $this->engine = $engine;
    $this->expediteurs = $expediteurs;
    $this->notifications = $notifications;
  }

  /**
   *
   *_parseDestinataires
   *
   * Analyse le type de destinataire passé à la notification
   * pour construire les adresses électroniques correspondantes
   *
   *
   * @version 0.1
   *
   * @param Nana | array | string $destinataires — liste des destinataires
   *
   * @return array | string
   *
   **/
  private function _parseDestinataires ($destinataires) {

    if ($destinataires instanceof Nana) {
      $to = $destinataires->geEmail();
    } elseif (is_array($destinataires)) {
      foreach ($destinataires as $d) {
        $to[] = $this->_parseDestinataires($d);
      }
    } elseif (filter_var($destinataires, FILTER_VALIDATE_EMAIL)) {
      $to = $destinataires;
    } elseif (!empty($this->notifications[$destinataires])) {
      $to = $this->notifications[$destinataires];
    } else {
      throw new \Exception("Le champ To: ne peut pas être vide", 1);
    }

    return $to;
  }

  /**
   * nana_notify 
   *
   * Construit et associe à un document les objets Tag issus de la liste
   * des mots-clefs donnés dans le cham de formulaire
   *
   * @version 0.1
   *
   * @param Nana $nana — destinataire du mail
   * @param array $metadata — Paramètres d'envoi du mail
   * > Schéma du template Twig (e.g. Causeuse:notification)
   * @param array $corps — Données à indérer dans le corps du mail
   * @param string $bcc — Destinataires d'une copie cachée (selon les paramètes système)
   *
   * @return boolean
   *
   */
  public function nana_notify($nana, $metadata, $corps, $bcc = NULL) {

    $this->metadata = array_merge($this->metadata, $metadata);
    
    $this->corps = array_merge($this->corps, $corps);
    $this->corps['destinataire'] = $nana->getUsername();
    $this->corps['dateEnvoi'] = new \Datetime;
    
    list($bundle, $template) = explode(":", $metadata['template']);
    $gabarit = sprintf($this->squelette_template, $bundle, $template);

    $message = \Swift_Message::newInstance();
    $message->setSubject('[Audrey] '.$metadata['sujet'])
            ->setContentType('text/html')
            ->setFrom($$this->expediteurs['redaction'])
            ->setTo($nana->getEmail())
            ->setBody(
                $this->render($gabarit, $this->corps),
                'text/html'
    );
    foreach($bcc as $destinataire) {
        $message->addBcc($destinataire);
    }

    return $this->mailer->send($message);
  }

  /**
   * admin_notify 
   *
   * Construit et associe à un document les objets Tag issus de la liste
   * des mots-clefs donnés dans le cham de formulaire
   *
   * @version 0.1
   *
   * @param Nana | string $to — destinataire(s) du mail
   * @param string $metadata — Paramètres d'envoi du mail
   * > Schéma du template Twig (e.g. Causeuse:notification)
   * @param array $corps — Données à indérer dans le corps du mail
   *
   * @return boolean
   *
   */
  public function admin_notify($destinataire, $metadata, $corps) {

    $this->metadata = array_merge($this->metadata, $metadata);
    
    $this->corps = array_merge($this->corps, $corps);
    $this->corps['dateEnvoi'] = new \Datetime;

    $to = $this->_parseDestinataires($destinataire);    

    list($bundle, $template) = explode(":", $metadata['template']);
    $gabarit = sprintf($this->squelette_template, $bundle, $template);

    $message = \Swift_Message::newInstance();
    $message->setSubject('[Audrey] '.$metadata['sujet'])
            ->setContentType('text/html')
            ->setFrom($this->expediteurs['admin'])
            ->setTo($to)
            ->setBody(
                $this->engine->render($gabarit, $this->corps),
                'text/html'
    );
    $err = $this->mailer->send($message, $erreurs);
    
    return $err;
  }

}
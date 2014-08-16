<?php

namespace TDN\Bundle\DocumentBundle\URL;

use Doctrine\Common\Collections\Collection;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\Request;

use TDN\Bundle\DocumentBundle\Entity\Document;

class URL {

  protected $routeur;

  public function __construct(Router $router)
  {
    $this->routeur = $router;
  }

   /**
   * Construit et associe à un document les objets Tag issus de la liste
   * des mots-clefs donnés dans le cham de formulaire
   *
   * @param Document $document
   */
  public function canonicalURL($document) {

    // $tags = $document->getTags();
	list($route, ,$params) = $document->getURLElements();
	$url = $this->routeur->generate($route, $params);

	return $url;
  }

  public function refererURL($referer = NULL) {
    $_backURL = $referer ?: $this->routeur->generate('Core_home');
    return $_backURL;
  }

}


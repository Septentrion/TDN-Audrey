<?php

namespace TDN\Bundle\DocumentBundle\Slider;

use Doctrine\Common\Collections\Collection;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\Request;

use TDN\Bundle\DocumentBundle\Entity\Document;
use TDN\Bundle\DocumentBundle\Entity\Slider;
use TDN\Bundle\ImageBundle\Entity\Image;

class SliderManager {

  // protected $routeur;
  protected $imageProcessor;
  protected $mediaRootFolder;

  public function __construct($imageProcessor, $mediaRootFolder)
  {
    // $this->routeur = $router;
    $this->imageProcessor = $imageProcessor;
    $this->mediaRootFolder = $mediaRootFolder;
  }

   /**
   * Construit et associe à un document les objets Tag issus de la liste
   * des mots-clefs donnés dans le cham de formulaire
   *
   * @param Document $document
   */
  public function assignSlider(Slider $slider, $document) {

    $slider->setup($_TDNDocument->getLnAuteur());
    $slider->setLnSource($_TDNDocument);
    $slider->setDatePublication($_TDNDocument->getDatePublication());
    if (is_null($slider->getStatut())) {
      $slider->setStatut(0);
    }

    $em->persist($slider);
     // Post-traitement de l'image
    if ($slider->getLnCover() instanceof Image) {
      $imageCover = $slider->getLnCover();
      $fichierImage = $slider->getLnCover()->getFichier();
      $_m = $imageCover->getDatePublication()->format('m');
      $_y = $imageCover->getDatePublication()->format('Y');
      $dossierSlider = '/public/'.$_y.'/'.$_m.'/';
      $source = $this->mediaRootFolder.$dossierSlider.$fichierImage;
      $err = $this->imageProcessor->downScale($source, 600, 'height');                  
      }
  }

}


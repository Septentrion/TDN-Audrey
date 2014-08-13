<?php

namespace TDN\Bundle\DocumentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use TDN\Bundle\ImageBundle\Entity\Image;

/**
 * TDN\Bundle\DocumentBundle\Entity\Slider
 */
class Slider
{
    /**
     * @var string $pitch
     */
    private $pitch;

    /**
     * @var integer $ordre
     */
    private $ordre;

    /**
     * @var integer $statut
     */
    private $statut;

    /**
     * @var \DateTime $datePublication
     */
    private $datePublication;

    /**
     * @var integer $idSlide
     */
    private $idSlide;

    /**
     * @var TDN\Bundle\DocumentBundle\Entity\Document
     */
    private $lnSource;

    /**
     * @var TDN\Bundle\ImageBundle\Entity\Image
     */
    private $lnCover;


    /**
     * Set pitch
     *
     * @param string $pitch
     * @return Slider
     */
    public function setPitch($pitch)
    {
        $this->pitch = $pitch;
    
        return $this;
    }

    /**
     * Get pitch
     *
     * @return string 
     */
    public function getPitch()
    {
        return $this->pitch;
    }

    /**
     * Set ordre
     *
     * @param integer $ordre
     * @return Slider
     */
    public function setOrdre($ordre)
    {
        $this->ordre = $ordre;
    
        return $this;
    }

    /**
     * Get ordre
     *
     * @return integer 
     */
    public function getOrdre()
    {
        return $this->ordre;
    }

    /**
     * Set statut
     *
     * @param integer $statut
     * @return Slider
     */
    public function setStatut($statut)
    {
        $this->statut = $statut;
    
        return $this;
    }

    /**
     * Get statut
     *
     * @return integer 
     */
    public function getStatut()
    {
        return $this->statut;
    }

    /**
     * Set datePublication
     *
     * @param \DateTime $datePublication
     * @return Slider
     */
    public function setDatePublication($datePublication)
    {
        $this->datePublication = $datePublication;
    
        return $this;
    }

    /**
     * Get datePublication
     *
     * @return \DateTime 
     */
    public function getDatePublication()
    {
        return $this->datePublication;
    }

    /**
     * Get idSlide
     *
     * @return integer 
     */
    public function getIdSlide()
    {
        return $this->idSlide;
    }

    /**
     * Set lnSource
     *
     * @param TDN\Bundle\DocumentBundle\Entity\Document $lnSource
     * @return Slider
     */
    public function setLnSource(\TDN\Bundle\DocumentBundle\Entity\Document $lnSource = null)
    {
        $this->lnSource = $lnSource;
    
        return $this;
    }

    /**
     * Get lnSource
     *
     * @return TDN\Bundle\DocumentBundle\Entity\Document 
     */
    public function getLnSource()
    {
        return $this->lnSource;
    }

    /**
     * Set lnCover
     *
     * @param TDN\Bundle\ImageBundle\Entity\Image $lnCover
     * @return Slider
     */
    public function setLnCover(\TDN\Bundle\ImageBundle\Entity\Image $lnCover = null)
    {
        $this->lnCover = $lnCover;
    
        return $this;
    }

    /**
     * Get lnCover
     *
     * @return TDN\Bundle\ImageBundle\Entity\Image 
     */
    public function getLnCover()
    {
        return $this->lnCover;
    }

    /**
     * Construit un nouveau slide
     *
     * @return Slider 
     */
    public function setup ($imageOwner = NULL) {

        // Création de l'illustration de l'article en une
        $imageSlider = $this->getLnCover();
        if ($imageSlider instanceof Image) {
            $now = new \DateTime;
            $dossier = '/public/'.$now->format('Y').'/'.$now->format('m').'/';
            $imageSlider->init($dossier, $imageOwner, $imageOwner);
        }   
        $this->setOrdre(0);
        if ($this->getStatut() == 1) {
            $this->setDatePublication(new \DateTime);
        }
        return $this;
    }

        /**
     * Construit un nouveau slide
     *
     * @return Slider 
     */
    public function make ($_TDNDocument) {

        $imageOwner = $_TDNDocument->getLnAuteur();
        // Création de l'illustration de l'article en une
        $imageSlider = $this->getLnCover();
        if ($imageSlider instanceof Image) {
            $now = new \DateTime;
            $dossier = '/public/'.$now->format('Y').'/'.$now->format('m').'/';
            $imageSlider->init($dossier, $imageOwner, $imageOwner);
        }   
        $this->setLnSource($_TDNDocument)->setDatePublication($_TDNDocument->getDatePublication());
        if (is_null($slider->getStatut())) {
            $this->setStatut(0);
        }
        $this->setOrdre(0);
        if ($this->getStatut() == 1) {
            $this->setDatePublication(new \DateTime);
        }
        return $this;
    }
}
<?php

namespace TDN\Bundle\ConseilExpertBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use TDN\Bundle\DocumentBundle\Entity\Document;

/**
 * TDN\Bundle\ConseilExpertBundle\Entity\ConseilExpert
 */
class ConseilExpert extends Document {
    /**
     * @var string $question
     */
    private $question;

    /**
     * @var integer $auteur
     */
    private $auteur;

    /**
     * @var string $reponse
     */
    private $reponse;

    /**
     * @var TDN\Bundle\NanaBundle\Entity\Nana
     */
    private $lnExpert;


    /**
     * Set question
     *
     * @param string $question
     * @return ConseilExpert
     */
    public function setQuestion($question)
    {
        $this->question = $question;
    
        return $this;
    }

    /**
     * Get question
     *
     * @return string 
     */
    public function getQuestion()
    {
        return $this->question;
    }

    /**
     * Set auteur
     *
     * @param integer $auteur
     * @return ConseilExpert
     */
    public function setAuteur($auteur)
    {
        $this->auteur = $auteur;
    
        return $this;
    }

    /**
     * Get auteur
     *
     * @return integer 
     */
    public function getAuteur()
    {
        return $this->auteur;
    }

    /**
     * Set reponse
     *
     * @param string $reponse
     * @return ConseilExpert
     */
    public function setReponse($reponse)
    {
        $this->reponse = $reponse;
    
        return $this;
    }

    /**
     * Get reponse
     *
     * @return string 
     */
    public function getReponse()
    {
        return $this->reponse;
    }

    /**
     * Set lnExpert
     *
     * @param TDN\Bundle\NanaBundle\Entity\Nana $lnExpert
     * @return ConseilExpert
     */
    public function setLnExpert(\TDN\Bundle\NanaBundle\Entity\Nana $lnExpert = null)
    {
        $this->lnExpert = $lnExpert;
    
        return $this;
    }

    /**
     * Get lnExpert
     *
     * @return TDN\Bundle\NanaBundle\Entity\Nana 
     */
    public function getLnExpert()
    {
        return $this->lnExpert;
    }

    /**
     * @var \DateTime $dateSoumission
     */
    private $dateSoumission;


    /**
     * Set dateSoumission
     *
     * @param \DateTime $dateSoumission
     * @return ConseilExpert
     */
    public function setDateSoumission($dateSoumission)
    {
        $this->dateSoumission = $dateSoumission;
    
        return $this;
    }

    /**
     * Get dateSoumission
     *
     * @return \DateTime 
     */
    public function getDateSoumission()
    {
        return $this->dateSoumission;
    }

    /**
     * @var TDN\Bundle\ImageBundle\Entity\Image
     */
    private $lnImage;


    /**
     * Set lnImage
     *
     * @param TDN\Bundle\ImageBundle\Entity\Image $lnImage
     * @return ConseilExpert
     */
    public function setLnImage(\TDN\Bundle\ImageBundle\Entity\Image $lnImage = null)
    {
        $this->lnImage = $lnImage;
    
        return $this;
    }

    /**
     * Get lnImage
     *
     * @return TDN\Bundle\ImageBundle\Entity\Image 
     */
    public function getLnImage()
    {
        return $this->lnImage;
    }

    public function init() {

        $this->setTitre("");
        $this->setSlug("");
        $this->setLikes(0);
        $this->setHits(0);
        $this->setAuteur(0);
        $this->setReponse('');
        $this->setStatut('CONSEIL_ENREGISTRE');
        $this->setVersion("1.0");
        $this->setTags("");
        $this->setDateSoumission(new \DateTime);
        $this->setDatePublication(new \DateTime);
        $this->setDateModification(new \DateTime);
        $this->setCommentThread(new \Doctrine\Common\Collections\ArrayCollection());

        return $this;
    }
}
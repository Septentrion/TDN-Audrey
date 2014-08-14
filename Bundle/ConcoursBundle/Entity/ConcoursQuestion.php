<?php

namespace TDN\Bundle\ConcoursBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TDN\Bundle\ConcoursBundle\Entity\ConcoursQuestion
 */
class ConcoursQuestion
{
    /**
     * @var string $question
     */
    private $question;

    /**
     * @var string $reponse
     */
    private $reponse;

    /**
     * @var integer $multiple
     */
    private $multiple;

    /**
     * @var boolean $exact
     */
    private $exact;

    /**
     * @var integer $idQuestion
     */
    private $idQuestion;

    /**
     * @var TDN\Bundle\ConcoursBundle\Entity\Concours
     */
    private $lnConcours;


    /**
     * Set question
     *
     * @param string $question
     * @return ConcoursQuestion
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
     * Set reponse
     *
     * @param string $reponse
     * @return ConcoursQuestion
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
     * Set multiple
     *
     * @param integer $multiple
     * @return ConcoursQuestion
     */
    public function setMultiple($multiple)
    {
        $this->multiple = $multiple;
    
        return $this;
    }

    /**
     * Get multiple
     *
     * @return integer 
     */
    public function getMultiple()
    {
        return $this->multiple;
    }

    /**
     * Set exact
     *
     * @param boolean $exact
     * @return ConcoursQuestion
     */
    public function setExact($exact)
    {
        $this->exact = $exact;
    
        return $this;
    }

    /**
     * Get exact
     *
     * @return boolean 
     */
    public function getExact()
    {
        return $this->exact;
    }

    /**
     * Get idQuestion
     *
     * @return integer 
     */
    public function getIdQuestion()
    {
        return $this->idQuestion;
    }

    /**
     * Set lnConcours
     *
     * @param TDN\Bundle\ConcoursBundle\Entity\Concours $lnConcours
     * @return ConcoursQuestion
     */
    public function setLnConcours(\TDN\Bundle\ConcoursBundle\Entity\Concours $lnConcours = null)
    {
        $this->lnConcours = $lnConcours;
    
        return $this;
    }

    /**
     * Get lnConcours
     *
     * @return TDN\Bundle\ConcoursBundle\Entity\Concours 
     */
    public function getLnConcours()
    {
        return $this->lnConcours;
    }
    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    private $filReponses;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->filReponses = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add filReponses
     *
     * @param TDN\Bundle\ConcoursBundle\Entity\ $filReponses
     * @return ConcoursQuestion
     */
    public function addFilReponse(\TDN\Bundle\ConcoursBundle\Entity\ConcoursReponse $filReponses)
    {
        $this->filReponses[] = $filReponses;
    
        return $this;
    }

    /**
     * Add filReponses
     *
     * @param TDN\Bundle\ConcoursBundle\Entity\ $filReponses
     * @return ConcoursQuestion
     */
    public function addFilReponseBack(\TDN\Bundle\ConcoursBundle\Entity\ConcoursReponse $reponse)
    {
         $reponse->setLnQuestion($this);
   
        return $this;
    }

    /**
     * Remove filReponses
     *
     * @param TDN\Bundle\ConcoursBundle\Entity\ $filReponses
     */
    public function removeFilReponse(\TDN\Bundle\ConcoursBundle\Entity\ConcoursReponse $filReponses)
    {
        $this->filReponses->removeElement($filReponses);
    }

    /**
     * Get filReponses
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getFilReponses()
    {
        return $this->filReponses;
    }
}
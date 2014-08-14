<?php

namespace TDN\Bundle\ConcoursBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TDN\Bundle\ConcoursBundle\Entity\ConcoursReponse
 */
class ConcoursReponse
{
    /**
     * @var string $reponse
     */
    private $reponse;

    /**
     * @var boolean $exact
     */
    private $exact;

    /**
     * @var integer $multiple
     */
    private $multiple;

    /**
     * @var integer $idReponse
     */
    private $idReponse;

    /**
     * @var TDN\Bundle\ConcoursBundle\Entity\ConcoursQuestion
     */
    private $lnQuestion;


    /**
     * Set reponse
     *
     * @param string $reponse
     * @return ConcoursReponse
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
     * Set exact
     *
     * @param boolean $exact
     * @return ConcoursReponse
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
     * Set multiple
     *
     * @param integer $multiple
     * @return ConcoursReponse
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
     * Get idReponse
     *
     * @return integer 
     */
    public function getIdReponse()
    {
        return $this->idReponse;
    }

    /**
     * Set lnQuestion
     *
     * @param TDN\Bundle\ConcoursBundle\Entity\ConcoursQuestion $lnQuestion
     * @return ConcoursReponse
     */
    public function setLnQuestion(\TDN\Bundle\ConcoursBundle\Entity\ConcoursQuestion $lnQuestion = null)
    {
        $this->lnQuestion = $lnQuestion;
    
        return $this;
    }

    /**
     * Get lnQuestion
     *
     * @return TDN\Bundle\ConcoursBundle\Entity\ConcoursQuestion 
     */
    public function getLnQuestion()
    {
        return $this->lnQuestion;
    }
}
<?php

namespace TDN\Bundle\CauseuseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use TDN\Bundle\DocumentBundle\Entity\Document;

/**
 * TDN\Bundle\CauseuseBundle\Entity\Question
 */
class Question extends Document {
    /**
     * @var string $question
     */
    private $question;

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
     * Set question
     *
     * @param string $question
     * @return Question
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
     * Add fil_reponses
     *
     * @param TDN\Bundle\CauseuseBundle\Entity\Reponse $filReponses
     * @return Question
     */
    public function addFilReponse(\TDN\Bundle\CauseuseBundle\Entity\Reponse $filReponses)
    {
        $this->filReponses[] = $filReponses;
    
        return $this;
    }

    /**
     * Remove fil_reponses
     *
     * @param TDN\Bundle\CauseuseBundle\Entity\Reponse $filReponses
     */
    public function removeFilReponse(\TDN\Bundle\CauseuseBundle\Entity\Reponse $filReponses)
    {
        $this->filReponses->removeElement($filReponses);
    }

    /**
     * Get fil_reponses
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getFilReponses()
    {
        return $this->filReponses;
    }
    /**
     * @var \DateTime $dateSoumission
     */
    private $dateSoumission;


    /**
     * Set dateSoumission
     *
     * @param \DateTime $dateSoumission
     * @return Question
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
     * @var integer $reponseAcceptee
     */
    private $reponseAcceptee;


    /**
     * Set reponseAcceptee
     *
     * @param integer $reponseAcceptee
     * @return Question
     */
    public function setReponseAcceptee($reponseAcceptee)
    {
        $this->reponseAcceptee = $reponseAcceptee;
    
        return $this;
    }

    /**
     * Get reponseAcceptee
     *
     * @return integer 
     */
    public function getReponseAcceptee()
    {
        return $this->reponseAcceptee;
    }
}
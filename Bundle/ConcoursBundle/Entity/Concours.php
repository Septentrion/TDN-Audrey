<?php

namespace TDN\Bundle\ConcoursBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use TDN\Bundle\DocumentBundle\Entity\Document;

/**
 * TDN\Bundle\ConcoursBundle\Entity\Concours
 */
class Concours extends Document {

    /**
     * @var string $corps
     */
    private $corps;

    /**
     * @var string $typeJeuConcours
     */
    private $typeJeuConcours;

    /**
     * @var string $mailParticipant
     */
    private $mailParticipant;

    /**
     * @var string $sponsor
     */
    private $sponsor;

    /**
     * @var string $gain
     */
    private $gain;

    /**
     * @var integer $nombreGagnants
     */
    private $nombreGagnants;

    /**
     * @var \DateTime $dateDebut
     */
    private $dateDebut;

    /**
     * @var \DateTime $dateArret
     */
    private $dateArret;

    /**
     * @var boolean $transmission
     */
    private $transmission;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    private $questions;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    private $participants;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->questions = new \Doctrine\Common\Collections\ArrayCollection();
        $this->participants = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Set corps
     *
     * @param string $corps
     * @return Concours
     */
    public function setCorps($corps)
    {
        $this->corps = $corps;
    
        return $this;
    }

    /**
     * Get corps
     *
     * @return string 
     */
    public function getCorps()
    {
        return $this->corps;
    }

    /**
     * Set typeJeuConcours
     *
     * @param string $typeJeuConcours
     * @return Concours
     */
    public function setTypeJeuConcours($typeJeuConcours)
    {
        $this->typeJeuConcours = $typeJeuConcours;
    
        return $this;
    }

    /**
     * Get typeJeuConcours
     *
     * @return string 
     */
    public function getTypeJeuConcours()
    {
        return $this->typeJeuConcours;
    }

    /**
     * Set mailParticipant
     *
     * @param string $mailParticipant
     * @return Concours
     */
    public function setMailParticipant($mailParticipant)
    {
        $this->mailParticipant = $mailParticipant;
    
        return $this;
    }

    /**
     * Get mailParticipant
     *
     * @return string 
     */
    public function getMailParticipant()
    {
        return $this->mailParticipant;
    }

    /**
     * Set sponsor
     *
     * @param string $sponsor
     * @return Concours
     */
    public function setSponsor($sponsor)
    {
        $this->sponsor = $sponsor;
    
        return $this;
    }

    /**
     * Get sponsor
     *
     * @return string 
     */
    public function getSponsor()
    {
        return $this->sponsor;
    }

    /**
     * Set gain
     *
     * @param string $gain
     * @return Concours
     */
    public function setGain($gain)
    {
        $this->gain = $gain;
    
        return $this;
    }

    /**
     * Get gain
     *
     * @return string 
     */
    public function getGain()
    {
        return $this->gain;
    }

    /**
     * Set nombreGagnants
     *
     * @param integer $nombreGagnants
     * @return Concours
     */
    public function setNombreGagnants($nombreGagnants)
    {
        $this->nombreGagnants = $nombreGagnants;
    
        return $this;
    }

    /**
     * Get nombreGagnants
     *
     * @return integer 
     */
    public function getNombreGagnants()
    {
        return $this->nombreGagnants;
    }

    /**
     * Set dateDebut
     *
     * @param \DateTime $dateDebut
     * @return Concours
     */
    public function setDateDebut($dateDebut)
    {
        $this->dateDebut = $dateDebut;
    
        return $this;
    }

    /**
     * Get dateDebut
     *
     * @return \DateTime 
     */
    public function getDateDebut()
    {
        return $this->dateDebut;
    }

    /**
     * Set dateArret
     *
     * @param \DateTime $dateArret
     * @return Concours
     */
    public function setDateArret($dateArret)
    {
        $this->dateArret = $dateArret;
    
        return $this;
    }

    /**
     * Get dateArret
     *
     * @return \DateTime 
     */
    public function getDateArret()
    {
        return $this->dateArret;
    }

    /**
     * Set transmission
     *
     * @param boolean $transmission
     * @return Concours
     */
    public function setTransmission($transmission)
    {
        $this->transmission = $transmission;
    
        return $this;
    }

    /**
     * Get transmission
     *
     * @return boolean 
     */
    public function getTransmission()
    {
        return $this->transmission;
    }

    /**
     * Add questions
     *
     * @param TDN\Bundle\ConcoursBundle\Entity\ConcoursQuestion $questions
     * @return Concours
     */
    public function addQuestion(\TDN\Bundle\ConcoursBundle\Entity\ConcoursQuestion $questions)
    {
        $this->questions[] = $questions;
    
        return $this;
    }

    /**
     * Add questions
     *
     * @param TDN\Bundle\ConcoursBundle\Entity\ConcoursQuestion $questions
     * @return Concours
     */
    public function addQuestionBack(\TDN\Bundle\ConcoursBundle\Entity\ConcoursQuestion $question)
    {
        $question->setLnConcours($this);
    
        return $this;
    }

    /**
     * Remove questions
     *
     * @param TDN\Bundle\ConcoursBundle\Entity\ConcoursQuestion $questions
     */
    public function removeQuestion(\TDN\Bundle\ConcoursBundle\Entity\ConcoursQuestion $questions)
    {
        $this->questions->removeElement($questions);
    }

    /**
     * Get questions
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getQuestions()
    {
        return $this->questions;
    }

    /**
     * Add participants
     *
     * @param TDN\Bundle\NanaBundle\Entity\Nana $participants
     * @return Concours
     */
    public function addParticipant(\TDN\Bundle\NanaBundle\Entity\Nana $participants)
    {
        $this->participants[] = $participants;
    
        return $this;
    }

    /**
     * Remove participants
     *
     * @param TDN\Bundle\NanaBundle\Entity\Nana $participants
     */
    public function removeParticipant(\TDN\Bundle\NanaBundle\Entity\Nana $participants)
    {
        $this->participants->removeElement($participants);
    }

    /**
     * Get participants
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getParticipants()
    {
        return $this->participants;
    }
    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    private $filParticipants;


    /**
     * Add filParticipants
     *
     * @param TDN\Bundle\ConcoursBundle\Entity\ConcoursParticipant $filParticipants
     * @return Concours
     */
    public function addFilParticipant(\TDN\Bundle\ConcoursBundle\Entity\ConcoursParticipant $filParticipants)
    {
        $this->filParticipants[] = $filParticipants;
    
        return $this;
    }

    /**
     * Remove filParticipants
     *
     * @param TDN\Bundle\ConcoursBundle\Entity\ConcoursParticipant $filParticipants
     */
    public function removeFilParticipant(\TDN\Bundle\ConcoursBundle\Entity\ConcoursParticipant $filParticipants)
    {
        $this->filParticipants->removeElement($filParticipants);
    }

    /**
     * Get filParticipants
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getFilParticipants()
    {
        return $this->filParticipants;
    }
    /**
     * @var boolean $open
     */
    private $open;


    /**
     * Set open
     *
     * @param boolean $open
     * @return Concours
     */
    public function setOpen($open)
    {
        $this->open = $open;
    
        return $this;
    }

    /**
     * Get open
     *
     * @return boolean 
     */
    public function getOpen()
    {
        return $this->open;
    }
    /**
     * @var boolean $reponsesVisibles
     */
    private $reponsesVisibles;


    /**
     * Set reponsesVisibles
     *
     * @param boolean $reponsesVisibles
     * @return Concours
     */
    public function setReponsesVisibles($reponsesVisibles)
    {
        $this->reponsesVisibles = $reponsesVisibles;
    
        return $this;
    }

    /**
     * Get reponsesVisibles
     *
     * @return boolean 
     */
    public function getReponsesVisibles()
    {
        return $this->reponsesVisibles;
    }
    /**
     * @var string $sponsorURL
     */
    private $sponsorURL;


    /**
     * Set sponsorURL
     *
     * @param string $sponsorURL
     * @return Concours
     */
    public function setSponsorURL($sponsorURL)
    {
        $this->sponsorURL = $sponsorURL;
    
        return $this;
    }

    /**
     * Get sponsorURL
     *
     * @return string 
     */
    public function getSponsorURL()
    {
        return $this->sponsorURL;
    }
}
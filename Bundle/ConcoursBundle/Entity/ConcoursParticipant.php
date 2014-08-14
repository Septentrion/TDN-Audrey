<?php

namespace TDN\Bundle\ConcoursBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TDN\Bundle\ConcoursBundle\Entity\ConcoursParticipant
 */
class ConcoursParticipant
{
    /**
     * @var string $reponse
     */
    private $reponse;

    /**
     * @var string $invitations
     */
    private $invitations;

    /**
     * @var float $poids
     */
    private $poids;

    /**
     * @var boolean $gagnant
     */
    private $gagnant;

    /**
     * @var \DateTime $dateParticipation
     */
    private $dateParticipation;

    /**
     * @var string $mailParticipant
     */
    private $mailParticipant;

    /**
     * @var integer $idParticipation
     */
    private $idParticipation;

    /**
     * @var TDN\Bundle\ConcoursBundle\Entity\Concours
     */
    private $lnConcours;

    /**
     * @var TDN\Bundle\NanaBundle\Entity\Nana
     */
    private $lnParticipant;


    /**
     * Set reponse
     *
     * @param string $reponse
     * @return ConcoursParticipant
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
     * Set invitations
     *
     * @param string $invitations
     * @return ConcoursParticipant
     */
    public function setInvitations($invitations)
    {
        $this->invitations = $invitations;
    
        return $this;
    }

    /**
     * Get invitations
     *
     * @return string 
     */
    public function getInvitations()
    {
        return $this->invitations;
    }

    /**
     * Set poids
     *
     * @param float $poids
     * @return ConcoursParticipant
     */
    public function setPoids($poids)
    {
        $this->poids = $poids;
    
        return $this;
    }

    /**
     * Get poids
     *
     * @return float 
     */
    public function getPoids()
    {
        return $this->poids;
    }

    /**
     * Set gagnant
     *
     * @param boolean $gagnant
     * @return ConcoursParticipant
     */
    public function setGagnant($gagnant)
    {
        $this->gagnant = $gagnant;
    
        return $this;
    }

    /**
     * Get gagnant
     *
     * @return boolean 
     */
    public function getGagnant()
    {
        return $this->gagnant;
    }

    /**
     * Set dateParticipation
     *
     * @param \DateTime $dateParticipation
     * @return ConcoursParticipant
     */
    public function setDateParticipation($dateParticipation)
    {
        $this->dateParticipation = $dateParticipation;
    
        return $this;
    }

    /**
     * Get dateParticipation
     *
     * @return \DateTime 
     */
    public function getDateParticipation()
    {
        return $this->dateParticipation;
    }

    /**
     * Set mailParticipant
     *
     * @param string $mailParticipant
     * @return ConcoursParticipant
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
     * Get idParticipation
     *
     * @return integer 
     */
    public function getIdParticipation()
    {
        return $this->idParticipation;
    }

    /**
     * Set lnConcours
     *
     * @param TDN\Bundle\ConcoursBundle\Entity\Concours $lnConcours
     * @return ConcoursParticipant
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
     * Set lnParticipant
     *
     * @param TDN\Bundle\NanaBundle\Entity\Nana $lnParticipant
     * @return ConcoursParticipant
     */
    public function setLnParticipant(\TDN\Bundle\NanaBundle\Entity\Nana $lnParticipant = null)
    {
        $this->lnParticipant = $lnParticipant;
    
        return $this;
    }

    /**
     * Get lnParticipant
     *
     * @return TDN\Bundle\NanaBundle\Entity\Nana 
     */
    public function getLnParticipant()
    {
        return $this->lnParticipant;
    }
    /**
     * @var integer $votes
     */
    private $votes;


    /**
     * Set votes
     *
     * @param integer $votes
     * @return ConcoursParticipant
     */
    public function setVotes($votes)
    {
        $this->votes = $votes;
    
        return $this;
    }

    /**
     * Get votes
     *
     * @return integer 
     */
    public function getVotes()
    {
        return $this->votes;
    }
}
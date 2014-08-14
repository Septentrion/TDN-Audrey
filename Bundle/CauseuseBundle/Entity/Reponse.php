<?php

namespace TDN\Bundle\CauseuseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use TDN\Bundle\DocumentBundle\Entity\Document;

/**
 * TDN\Bundle\CauseuseBundle\Entity\Reponse
 */
class Reponse extends Document {
    /**
     * @var string $reponse
     */
    private $reponse;

    /**
     * @var TDN\Bundle\CauseuseBundle\Entity\Question
     */
    private $lnConversation;


    /**
     * Set reponse
     *
     * @param string $reponse
     * @return Reponse
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
     * Set lnConversation
     *
     * @param TDN\Bundle\CauseuseBundle\Entity\Question $lnConversation
     * @return Reponse
     */
    public function setLnConversation(\TDN\Bundle\CauseuseBundle\Entity\Question $lnConversation = null)
    {
        $this->lnConversation = $lnConversation;
    
        return $this;
    }

    /**
     * Get lnConversation
     *
     * @return TDN\Bundle\CauseuseBundle\Entity\Question 
     */
    public function getLnConversation()
    {
        return $this->lnConversation;
    }
}
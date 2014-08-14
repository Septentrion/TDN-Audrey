<?php

namespace TDN\Bundle\NewsletterBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TDN\Bundle\NewsletterBundle\Entity\AbonneNewsletter
 */
class AbonneNewsletter
{
    /**
     * @var \DateTime $dateInscription
     */
    private $dateInscription;

    /**
     * @var string $email
     */
    private $email;


    /**
     * Set dateInscription
     *
     * @param \DateTime $dateInscription
     * @return AbonneNewsletter
     */
    public function setDateInscription($dateInscription)
    {
        $this->dateInscription = $dateInscription;
    
        return $this;
    }

    /**
     * Get dateInscription
     *
     * @return \DateTime 
     */
    public function getDateInscription()
    {
        return $this->dateInscription;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return AbonneNewsletter
     */
    public function setEmail($email)
    {
        $this->email = $email;
    
        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }
}
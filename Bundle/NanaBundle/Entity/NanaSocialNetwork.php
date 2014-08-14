<?php

namespace TDN\Bundle\NanaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TDN\Bundle\NanaBundle\Entity\NanaSocialNetwork
 */
class NanaSocialNetwork
{
    /**
     * @var string $plateforme
     */
    private $plateforme;

    /**
     * @var string $userID
     */
    private $userID;

    /**
     * @var string $id
     */
    private $id;

    /**
     * @var TDN\Bundle\NanaBundle\Entity\Nana
     */
    private $lnIdentite;


    /**
     * Set plateforme
     *
     * @param string $plateforme
     * @return NanaSocialNetwork
     */
    public function setPlateforme($plateforme)
    {
        $this->plateforme = $plateforme;
    
        return $this;
    }

    /**
     * Get plateforme
     *
     * @return string 
     */
    public function getPlateforme()
    {
        return $this->plateforme;
    }

    /**
     * Set userID
     *
     * @param string $userID
     * @return NanaSocialNetwork
     */
    public function setUserID($userID)
    {
        $this->userID = $userID;
    
        return $this;
    }

    /**
     * Get userID
     *
     * @return string 
     */
    public function getUserID()
    {
        return $this->userID;
    }

    /**
     * Get id
     *
     * @return string 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set lnIdentite
     *
     * @param TDN\Bundle\NanaBundle\Entity\Nana $lnIdentite
     * @return NanaSocialNetwork
     */
    public function setLnIdentite(\TDN\Bundle\NanaBundle\Entity\Nana $lnIdentite = null)
    {
        $this->lnIdentite = $lnIdentite;
    
        return $this;
    }

    /**
     * Get lnIdentite
     *
     * @return TDN\Bundle\NanaBundle\Entity\Nana 
     */
    public function getLnIdentite()
    {
        return $this->lnIdentite;
    }
}
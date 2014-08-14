<?php

namespace TDN\Bundle\NanaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TDN\Bundle\NanaBundle\Entity\NanaHobby
 */
class NanaHobby
{
    /**
     * @var string $domaine
     */
    private $domaine;

    /**
     * @var string $precisions
     */
    private $precisions;

    /**
     * @var integer $idHobby
     */
    private $idHobby;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    private $galerieHobby;

    /**
     * @var TDN\Bundle\NanaBundle\Entity\Nana
     */
    private $lnOwner;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->galerieHobby = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Set domaine
     *
     * @param string $domaine
     * @return NanaHobby
     */
    public function setDomaine($domaine)
    {
        $this->domaine = $domaine;
    
        return $this;
    }

    /**
     * Get domaine
     *
     * @return string 
     */
    public function getDomaine()
    {
        return $this->domaine;
    }

    /**
     * Set precisions
     *
     * @param string $precisions
     * @return NanaHobby
     */
    public function setPrecisions($precisions)
    {
        $this->precisions = $precisions;
    
        return $this;
    }

    /**
     * Get precisions
     *
     * @return string 
     */
    public function getPrecisions()
    {
        return $this->precisions;
    }

    /**
     * Get idHobby
     *
     * @return integer 
     */
    public function getIdHobby()
    {
        return $this->idHobby;
    }

    /**
     * Add galerieHobby
     *
     * @param TDN\Bundle\NanaBundle\Entity\NanaHobbyImageProxy $galerieHobby
     * @return NanaHobby
     */
    public function addGalerieHobby(\TDN\Bundle\NanaBundle\Entity\NanaHobbyImageProxy $galerieHobby)
    {
        $this->galerieHobby[] = $galerieHobby;
    
        return $this;
    }

    /**
     * Remove galerieHobby
     *
     * @param TDN\Bundle\NanaBundle\Entity\NanaHobbyImageProxy $galerieHobby
     */
    public function removeGalerieHobby(\TDN\Bundle\NanaBundle\Entity\NanaHobbyImageProxy $galerieHobby)
    {
        $this->galerieHobby->removeElement($galerieHobby);
    }

    /**
     * Get galerieHobby
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getGalerieHobby()
    {
        return $this->galerieHobby;
    }

    /**
     * Set lnOwner
     *
     * @param TDN\Bundle\NanaBundle\Entity\Nana $lnOwner
     * @return NanaHobby
     */
    public function setLnOwner(\TDN\Bundle\NanaBundle\Entity\Nana $lnOwner = null)
    {
        $this->lnOwner = $lnOwner;
    
        return $this;
    }

    /**
     * Get lnOwner
     *
     * @return TDN\Bundle\NanaBundle\Entity\Nana 
     */
    public function getLnOwner()
    {
        return $this->lnOwner;
    }
}
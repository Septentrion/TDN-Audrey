<?php

namespace TDN\Bundle\NanaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TDN\Bundle\NanaBundle\Entity\NanaHobbyImageProxy
 */
class NanaHobbyImageProxy
{
    /**
     * @var integer $idImageHobby
     */
    private $idImageHobby;

    /**
     * @var TDN\Bundle\NanaBundle\Entity\NanaHobby
     */
    private $lnHobby;


    /**
     * Get idImageHobby
     *
     * @return integer 
     */
    public function getIdImageHobby()
    {
        return $this->idImageHobby;
    }

    /**
     * Set lnHobby
     *
     * @param TDN\Bundle\NanaBundle\Entity\NanaHobby $lnHobby
     * @return NanaHobbyImageProxy
     */
    public function setLnHobby(\TDN\Bundle\NanaBundle\Entity\NanaHobby $lnHobby = null)
    {
        $this->lnHobby = $lnHobby;
    
        return $this;
    }

    /**
     * Get lnHobby
     *
     * @return TDN\Bundle\NanaBundle\Entity\NanaHobby 
     */
    public function getLnHobby()
    {
        return $this->lnHobby;
    }
    /**
     * @var TDN\Bundle\NanaBundle\Entity\
     */
    private $lnImage;


    /**
     * Set lnImage
     *
     * @param TDN\Bundle\ImageBundle\Entity\Image $lnImage
     * @return NanaHobbyImageProxy
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
}
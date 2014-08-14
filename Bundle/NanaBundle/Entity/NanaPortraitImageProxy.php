<?php

namespace TDN\Bundle\NanaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TDN\Bundle\NanaBundle\Entity\NanaPortraitImageProxy
 */
class NanaPortraitImageProxy
{
    /**
     * @var boolean $isAvatar
     */
    private $isAvatar;

    /**
     * @var integer $idPortrait
     */
    private $idPortrait;

    /**
     * @var TDN\Bundle\NanaBundle\Entity\Nana
     */
    private $lnPortrait;


    /**
     * Set isAvatar
     *
     * @param boolean $isAvatar
     * @return NanaPortraitImageProxy
     */
    public function setIsAvatar($isAvatar)
    {
        $this->isAvatar = $isAvatar;
    
        return $this;
    }

    /**
     * Get isAvatar
     *
     * @return boolean 
     */
    public function getIsAvatar()
    {
        return $this->isAvatar;
    }

    /**
     * Get idPortrait
     *
     * @return integer 
     */
    public function getIdPortrait()
    {
        return $this->idPortrait;
    }

    /**
     * Set lnPortrait
     *
     * @param TDN\Bundle\NanaBundle\Entity\Nana $lnPortrait
     * @return NanaPortraitImageProxy
     */
    public function setLnPortrait(\TDN\Bundle\NanaBundle\Entity\Nana $lnPortrait = null)
    {
        $this->lnPortrait = $lnPortrait;
    
        return $this;
    }

    /**
     * Get lnPortrait
     *
     * @return TDN\Bundle\NanaBundle\Entity\Nana 
     */
    public function getLnPortrait()
    {
        return $this->lnPortrait;
    }
    /**
     * @var TDN\Bundle\ImageBundle\Entity\Image
     */
    private $lnImage;


    /**
     * Set lnImage
     *
     * @param TDN\Bundle\ImageBundle\Entity\Image $lnImage
     * @return NanaPortraitImageProxy
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
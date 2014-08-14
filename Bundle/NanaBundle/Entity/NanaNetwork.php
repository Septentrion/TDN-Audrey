<?php

namespace TDN\Bundle\NanaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TDN\Bundle\NanaBundle\Entity\NanaNetwork
 */
class NanaNetwork
{
    /**
     * @var string $service
     */
    private $service;

    /**
     * @var string $url
     */
    private $url;

    /**
     * @var integer $idSocial
     */
    private $idSocial;

    /**
     * @var TDN\Bundle\NanaBundle\Entity\Nana
     */
    private $idOwner;


    /**
     * Set service
     *
     * @param string $service
     * @return NanaNetwork
     */
    public function setService($service)
    {
        $this->service = $service;
    
        return $this;
    }

    /**
     * Get service
     *
     * @return string 
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * Set url
     *
     * @param string $url
     * @return NanaNetwork
     */
    public function setUrl($url)
    {
        $this->url = $url;
    
        return $this;
    }

    /**
     * Get url
     *
     * @return string 
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Get idSocial
     *
     * @return integer 
     */
    public function getIdSocial()
    {
        return $this->idSocial;
    }

    /**
     * Set idOwner
     *
     * @param TDN\Bundle\NanaBundle\Entity\Nana $idOwner
     * @return NanaNetwork
     */
    public function setIdOwner(\TDN\Bundle\NanaBundle\Entity\Nana $idOwner = null)
    {
        $this->idOwner = $idOwner;
    
        return $this;
    }

    /**
     * Get idOwner
     *
     * @return TDN\Bundle\NanaBundle\Entity\Nana 
     */
    public function getIdOwner()
    {
        return $this->idOwner;
    }
}
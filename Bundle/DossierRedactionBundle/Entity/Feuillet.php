<?php

namespace TDN\Bundle\DossierRedactionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use TDN\Bundle\DocumentBundle\Entity\Document;

/**
 * TDN\Bundle\DossierRedactionBundle\Entity\Feuillet
 */
class Feuillet extends Document {
    /**
     * @var integer $ordre
     */
    private $ordre;

    /**
     * @var string $corps
     */
    private $corps;

    /**
     * @var TDN\Bundle\DossierRedactionBundle\Entity\Dossier
     */
    private $inFascicule;


    /**
     * Set ordre
     *
     * @param integer $ordre
     * @return Feuillet
     */
    public function setOrdre($ordre)
    {
        $this->ordre = $ordre;
    
        return $this;
    }

    /**
     * Get ordre
     *
     * @return integer 
     */
    public function getOrdre()
    {
        return $this->ordre;
    }

    /**
     * Set corps
     *
     * @param string $corps
     * @return Feuillet
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
     * Set inFascicule
     *
     * @param TDN\Bundle\DossierRedactionBundle\Entity\Dossier $inFascicule
     * @return Feuillet
     */
    public function setInFascicule(\TDN\Bundle\DossierRedactionBundle\Entity\Dossier $inFascicule = null)
    {
        $this->inFascicule = $inFascicule;
    
        return $this;
    }

    /**
     * Get inFascicule
     *
     * @return TDN\Bundle\DossierRedactionBundle\Entity\Dossier 
     */
    public function getInFascicule()
    {
        return $this->inFascicule;
    }
}

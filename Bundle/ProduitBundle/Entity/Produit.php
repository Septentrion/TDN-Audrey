<?php

namespace TDN\Bundle\ProduitBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use TDN\Bundle\DocumentBundle\Entity\Document;

/**
 * TDN\Bundle\ProduitBundle\Entity\Produit
 */
class Produit extends Document {
    /**
     * @var string $marque
     */
    private $marque;

    /**
     * @var float $prix
     */
    private $prix;

    /**
     * @var string $monnaie
     */
    private $monnaie;

    /**
     * @var string $url
     */
    private $url;

    /**
     * @var boolean $favori
     */
    private $favori;

    /**
     * @var string $critique
     */
    private $critique;

    /**
     * @var TDN\Bundle\RedactionBundle\Entity\SelectionShopping
     */
    private $lnSelection;


    /**
     * Set marque
     *
     * @param string $marque
     * @return Produit
     */
    public function setMarque($marque)
    {
        $this->marque = $marque;
    
        return $this;
    }

    /**
     * Get marque
     *
     * @return string 
     */
    public function getMarque()
    {
        return $this->marque;
    }

    /**
     * Set prix
     *
     * @param float $prix
     * @return Produit
     */
    public function setPrix($prix)
    {
        $this->prix = $prix;
    
        return $this;
    }

    /**
     * Get prix
     *
     * @return float 
     */
    public function getPrix()
    {
        return $this->prix;
    }

    /**
     * Set monnaie
     *
     * @param string $monnaie
     * @return Produit
     */
    public function setMonnaie($monnaie)
    {
        $this->monnaie = $monnaie;
    
        return $this;
    }

    /**
     * Get monnaie
     *
     * @return string 
     */
    public function getMonnaie()
    {
        return $this->monnaie;
    }

    /**
     * Set url
     *
     * @param string $url
     * @return Produit
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
     * Set favori
     *
     * @param boolean $favori
     * @return Produit
     */
    public function setFavori($favori)
    {
        $this->favori = $favori;
    
        return $this;
    }

    /**
     * Get favori
     *
     * @return boolean 
     */
    public function getFavori()
    {
        return $this->favori;
    }

    /**
     * Set critique
     *
     * @param string $critique
     * @return Produit
     */
    public function setCritique($critique)
    {
        $this->critique = $critique;
    
        return $this;
    }

    /**
     * Get critique
     *
     * @return string 
     */
    public function getCritique()
    {
        return $this->critique;
    }

    /**
     * Set idSelection
     *
     * @param TDN\Bundle\RedactionBundle\Entity\SelectionShopping $idSelection
     * @return Produit
     */
    public function setLnSelection(\TDN\Bundle\RedactionBundle\Entity\SelectionShopping $lnSelection = null)
    {
        $this->lnSelection = $lnSelection;
    
        return $this;
    }

    /**
     * Get idSelection
     *
     * @return TDN\Bundle\RedactionBundle\Entity\SelectionShopping 
     */
    public function getLnSelection()
    {
        return $this->lnSelection;
    }
    /**
     * @var string $codePromoTDN
     */
    private $codePromoTDN;


    /**
     * Set codePromoTDN
     *
     * @param string $codePromoTDN
     * @return Produit
     */
    public function setCodePromoTDN($codePromoTDN)
    {
        $this->codePromoTDN = $codePromoTDN;
    
        return $this;
    }

    /**
     * Get codePromoTDN
     *
     * @return string 
     */
    public function getCodePromoTDN()
    {
        return $this->codePromoTDN;
    }
    /**
     * @var TDN\Bundle\RedactionBundle\Entity\Article
     */
    private $citation;


    /**
     * Set citation
     *
     * @param TDN\Bundle\RedactionBundle\Entity\Article $citation
     * @return Produit
     */
    public function setCitation(\TDN\Bundle\RedactionBundle\Entity\Article $citation = null)
    {
        $this->citation = $citation;
    
        return $this;
    }

    /**
     * Get citation
     *
     * @return TDN\Bundle\RedactionBundle\Entity\Article 
     */
    public function getCitation()
    {
        return $this->citation;
    }
}
<?php

namespace TDN\Bundle\RedactionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use TDN\Bundle\DocumentBundle\Entity\Document;

/**
 * TDN\Bundle\RedactionBundle\Entity\SelectionShopping
 */
class SelectionShopping extends Document {
    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    private $setProduit;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->setProduit = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add setProduit
     *
     * @param TDN\Bundle\RedactionBundle\Entity\Produit $setProduit
     * @return SelectionShopping
     */
    public function addSetProduit(\TDN\Bundle\ProduitBundle\Entity\Produit $setProduit)
    {
        $this->setProduit[] = $setProduit;
    
        return $this;
    }

    /**
     * Remove setProduit
     *
     * @param TDN\Bundle\RedactionBundle\Entity\Produit $setProduit
     */
    public function removeSetProduit(\TDN\Bundle\ProduitBundle\Entity\Produit $setProduit)
    {
        $this->setProduit->removeElement($setProduit);
    }

    /**
     * Get setProduit
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getSetProduit()
    {
        return $this->setProduit;
    }
}

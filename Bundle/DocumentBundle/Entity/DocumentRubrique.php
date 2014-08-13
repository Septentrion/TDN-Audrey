<?php

namespace TDN\Bundle\DocumentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TDN\Bundle\DocumentBundle\Entity\DocumentRubrique
 */
class DocumentRubrique
{
    /**
     * @var string $titre
     */
    private $titre;

    /**
     * @var string $slug
     */
    private $slug;

    /**
     * @var string $abstract
     */
    private $abstract;

    /**
     * @var integer $parent
     */
    private $parent;

    /**
     * @var string $couleur
     */
    private $couleur;

    /**
     * @var string $sponsorImage
     */
    private $sponsorImage;

    /**
     * @var string $sponsorLink
     */
    private $sponsorLink;

    /**
     * @var integer $statut
     */
    private $statut;

    /**
     * @var \DateTime $datePublication
     */
    private $datePublication;

    /**
     * @var \DateTime $dateModification
     */
    private $dateModification;

    /**
     * @var integer $idRubrique
     */
    private $idRubrique;


    /**
     * Set titre
     *
     * @param string $titre
     * @return DocumentRubrique
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;
    
        return $this;
    }

    /**
     * Get titre
     *
     * @return string 
     */
    public function getTitre()
    {
        return $this->titre;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return DocumentRubrique
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    
        return $this;
    }

    /**
     * Get slug
     *
     * @return string 
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Get slug ou slug de la rubrique parente
     *
     * @return string 
     */
    public function getSuperSlug()
    {
        if (is_object($this->rubriqueParente)) {
            return $this->rubriqueParente->getSlug();
        } else {
            return $this->slug;
        }
    }

    /**
     * Set abstract
     *
     * @param string $abstract
     * @return DocumentRubrique
     */
    public function setAbstract($abstract)
    {
        $this->abstract = $abstract;
    
        return $this;
    }

    /**
     * Get abstract
     *
     * @return string 
     */
    public function getAbstract()
    {
        return $this->abstract;
    }

    /**
     * Set parent
     *
     * @param integer $parent
     * @return DocumentRubrique
     */
    public function setParent($parent)
    {
        $this->parent = $parent;
    
        return $this;
    }

    /**
     * Get parent
     *
     * @return integer 
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set couleur
     *
     * @param string $couleur
     * @return DocumentRubrique
     */
    public function setCouleur($couleur)
    {
        $this->couleur = $couleur;
    
        return $this;
    }

    /**
     * Get couleur
     *
     * @return string 
     */
    public function getCouleur()
    {
        return $this->couleur;
    }

    /**
     * Set sponsorImage
     *
     * @param string $sponsorImage
     * @return DocumentRubrique
     */
    public function setSponsorImage($sponsorImage)
    {
        $this->sponsorImage = $sponsorImage;
    
        return $this;
    }

    /**
     * Get sponsorImage
     *
     * @return string 
     */
    public function getSponsorImage()
    {
        return $this->sponsorImage;
    }

    /**
     * Set sponsorLink
     *
     * @param string $sponsorLink
     * @return DocumentRubrique
     */
    public function setSponsorLink($sponsorLink)
    {
        $this->sponsorLink = $sponsorLink;
    
        return $this;
    }

    /**
     * Get sponsorLink
     *
     * @return string 
     */
    public function getSponsorLink()
    {
        return $this->sponsorLink;
    }

    /**
     * Set statut
     *
     * @param integer $statut
     * @return DocumentRubrique
     */
    public function setStatut($statut)
    {
        $this->statut = $statut;
    
        return $this;
    }

    /**
     * Get statut
     *
     * @return integer 
     */
    public function getStatut()
    {
        return $this->statut;
    }

    /**
     * Set datePublication
     *
     * @param \DateTime $datePublication
     * @return DocumentRubrique
     */
    public function setDatePublication($datePublication)
    {
        $this->datePublication = $datePublication;
    
        return $this;
    }

    /**
     * Get datePublication
     *
     * @return \DateTime 
     */
    public function getDatePublication()
    {
        return $this->datePublication;
    }

    /**
     * Set dateModification
     *
     * @param \DateTime $dateModification
     * @return DocumentRubrique
     */
    public function setDateModification($dateModification)
    {
        $this->dateModification = $dateModification;
    
        return $this;
    }

    /**
     * Get dateModification
     *
     * @return \DateTime 
     */
    public function getDateModification()
    {
        return $this->dateModification;
    }

    /**
     * Get idRubrique
     *
     * @return integer 
     */
    public function getIdRubrique()
    {
        return $this->idRubrique;
    }
    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    private $sousRubriques;

    /**
     * @var TDN\Bundle\DocumentBundle\Entity\DocumentRubrique
     */
    private $rubriqueParente;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->sousRubriques = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add sousRubriques
     *
     * @param TDN\Bundle\DocumentBundle\Entity\DocumentRubrique $sousRubriques
     * @return DocumentRubrique
     */
    public function addSousRubrique(\TDN\Bundle\DocumentBundle\Entity\DocumentRubrique $sousRubriques)
    {
        $this->sousRubriques[] = $sousRubriques;
    
        return $this;
    }

    /**
     * Remove sousRubriques
     *
     * @param TDN\Bundle\DocumentBundle\Entity\DocumentRubrique $sousRubriques
     */
    public function removeSousRubrique(\TDN\Bundle\DocumentBundle\Entity\DocumentRubrique $sousRubriques)
    {
        $this->sousRubriques->removeElement($sousRubriques);
    }

    /**
     * Get sousRubriques
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getSousRubriques()
    {
        return $this->sousRubriques;
    }

    /**
     * Set rubriqueParente
     *
     * @param TDN\Bundle\DocumentBundle\Entity\DocumentRubrique $rubriqueParente
     * @return DocumentRubrique
     */
    public function setRubriqueParente(\TDN\Bundle\DocumentBundle\Entity\DocumentRubrique $rubriqueParente = null)
    {
        $this->rubriqueParente = $rubriqueParente;
    
        return $this;
    }

    /**
     * Get rubriqueParente
     *
     * @return TDN\Bundle\DocumentBundle\Entity\DocumentRubrique 
     */
    public function getRubriqueParente()
    {
        return $this->rubriqueParente;
    }
    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    private $setDomaines;


    /**
     * Add setDomaines
     *
     * @param TDN\Bundle\NanaBundle\Entity\NanaExpertise $setDomaines
     * @return DocumentRubrique
     */
    public function addSetDomaine(\TDN\Bundle\NanaBundle\Entity\NanaExpertise $setDomaines)
    {
        $this->setDomaines[] = $setDomaines;
    
        return $this;
    }

    /**
     * Remove setDomaines
     *
     * @param TDN\Bundle\NanaBundle\Entity\NanaExpertise $setDomaines
     */
    public function removeSetDomaine(\TDN\Bundle\NanaBundle\Entity\NanaExpertise $setDomaines)
    {
        $this->setDomaines->removeElement($setDomaines);
    }

    /**
     * Get setDomaines
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getSetDomaines()
    {
        return $this->setDomaines;
    }
}
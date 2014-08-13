<?php

namespace TDN\Bundle\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TDN\Bundle\CoreBundle\Entity\Journal
 */
class Journal
{
    /**
     * @var string $action
     */
    private $action;

    /**
     * @var string $url
     */
    private $url;

    /**
     * @var string $texte
     */
    private $texte;

    /**
     * @var string $support
     */
    private $support;

    /**
     * @var s $rubrique
     */
    private $rubrique;

    /**
     * @var \DateTime $dateEntree
     */
    private $dateEntree;

    /**
     * @var integer $id
     */
    private $id;

    /**
     * @var TDN\Bundle\NanaBundle\Entity\Nana
     */
    private $lnActeur;

    /**
     * @var TDN\Bundle\DocumentBundle\Entity\DocumentRubrique
     */
    private $lnRubrique;


    /**
     * Set action
     *
     * @param string $action
     * @return Journal
     */
    public function setAction($action)
    {
        $this->action = $action;
    
        return $this;
    }

    /**
     * Get action
     *
     * @return string 
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * Set url
     *
     * @param string $url
     * @return Journal
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
     * Set texte
     *
     * @param string $texte
     * @return Journal
     */
    public function setTexte($texte)
    {
        $this->texte = $texte;
    
        return $this;
    }

    /**
     * Get texte
     *
     * @return string 
     */
    public function getTexte()
    {
        return $this->texte;
    }

    /**
     * Set support
     *
     * @param string $support
     * @return Journal
     */
    public function setSupport($support)
    {
        $this->support = $support;
    
        return $this;
    }

    /**
     * Get support
     *
     * @return string 
     */
    public function getSupport()
    {
        return $this->support;
    }

    /**
     * Set rubrique
     *
     * @param s $rubrique
     * @return Journal
     */
    public function setRubrique(\s $rubrique)
    {
        $this->rubrique = $rubrique;
    
        return $this;
    }

    /**
     * Get rubrique
     *
     * @return s 
     */
    public function getRubrique()
    {
        return $this->rubrique;
    }

    /**
     * Set dateEntree
     *
     * @param \DateTime $dateEntree
     * @return Journal
     */
    public function setDateEntree($dateEntree)
    {
        $this->dateEntree = $dateEntree;
    
        return $this;
    }

    /**
     * Get dateEntree
     *
     * @return \DateTime 
     */
    public function getDateEntree()
    {
        return $this->dateEntree;
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set lnActeur
     *
     * @param TDN\Bundle\NanaBundle\Entity\Nana $lnActeur
     * @return Journal
     */
    public function setLnActeur(\TDN\Bundle\NanaBundle\Entity\Nana $lnActeur = null)
    {
        $this->lnActeur = $lnActeur;
    
        return $this;
    }

    /**
     * Get lnActeur
     *
     * @return TDN\Bundle\NanaBundle\Entity\Nana 
     */
    public function getLnActeur()
    {
        return $this->lnActeur;
    }

    /**
     * Set lnRubrique
     *
     * @param TDN\Bundle\DocumentBundle\Entity\DocumentRubrique $lnRubrique
     * @return Journal
     */
    public function setLnRubrique(\TDN\Bundle\DocumentBundle\Entity\DocumentRubrique $lnRubrique = null)
    {
        $this->lnRubrique = $lnRubrique;
    
        return $this;
    }

    /**
     * Get lnRubrique
     *
     * @return TDN\Bundle\DocumentBundle\Entity\DocumentRubrique 
     */
    public function getLnRubrique()
    {
        return $this->lnRubrique;
    }
    /**
     * @var string $titre
     */
    private $titre;


    /**
     * Set titre
     *
     * @param string $titre
     * @return Journal
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
     * @var TDN\Bundle\NanaBundle\Entity\Nana
     */
    private $lnVeilleur;


    /**
     * Set lnVeilleur
     *
     * @param TDN\Bundle\NanaBundle\Entity\Nana $lnVeilleur
     * @return Journal
     */
    public function setLnVeilleur(\TDN\Bundle\NanaBundle\Entity\Nana $lnVeilleur = null)
    {
        $this->lnVeilleur = $lnVeilleur;
    
        return $this;
    }

    /**
     * Get lnVeilleur
     *
     * @return TDN\Bundle\NanaBundle\Entity\Nana 
     */
    public function getLnVeilleur()
    {
        return $this->lnVeilleur;
    }
}
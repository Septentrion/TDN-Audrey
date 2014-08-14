<?php

namespace TDN\Bundle\NewsletterBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TDN\Bundle\NewsletterBundle\Entity\Newsletter
 */
class Newsletter
{
    /**
     * @var string $titre
     */
    private $titre;

    /**
     * @var string $editorial
     */
    private $editorial;

    /**
     * @var \DateTime $datePublication
     */
    private $datePublication;

    /**
     * @var string $statut
     */
    private $statut;

    /**
     * @var string $envoyes
     */
    private $envoyes;

    /**
     * @var string $AstroLoveBélier
     */
    private $AstroLoveBelier;

    /**
     * @var string $AstroLoveTaureau
     */
    private $AstroLoveTaureau;

    /**
     * @var string $AstroLoveGemeaux
     */
    private $AstroLoveGemeaux;

    /**
     * @var string $AstroLoveCancer
     */
    private $AstroLoveCancer;

    /**
     * @var string $AstroLoveLion
     */
    private $AstroLoveLion;

    /**
     * @var string $AstroLoveVierge
     */
    private $AstroLoveVierge;

    /**
     * @var string $AstroLoveBalance
     */
    private $AstroLoveBalance;

    /**
     * @var string $AstroLoveScorpion
     */
    private $AstroLoveScorpion;

    /**
     * @var string $AstroLoveSagittaire
     */
    private $AstroLoveSagittaire;

    /**
     * @var string $AstroLoveCapricorne
     */
    private $AstroLoveCapricorne;

    /**
     * @var string $AstroLoveVerseau
     */
    private $AstroLoveVerseau;

    /**
     * @var string $AstroLovePoissons
     */
    private $AstroLovePoissons;

    /**
     * @var integer $id
     */
    private $id;

    /**
     * @var TDN\Bundle\RedactionBundle\Entity\Article
     */
    private $lnBonPlan;

    /**
     * @var TDN\Bundle\RedactionBundle\Entity\Article
     */
    private $lnVideo;

    /**
     * @var TDN\Bundle\NanaBundle\Entity\Nana
     */
    private $lnAuteur;

    /**
     * @var TDN\Bundle\RedactionBundle\Entity\Article
     */
    private $lnArticleSponsor;

    /**
     * @var TDN\Bundle\DocumentBundle\Entity\Document
     */
    private $lnLire;

    /**
     * @var TDN\Bundle\CauseuseBundle\Entity\Question
     */
    private $lnQuestion;


    /**
     * Set titre
     *
     * @param string $titre
     * @return Newsletter
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
     * Set editorial
     *
     * @param string $editorial
     * @return Newsletter
     */
    public function setEditorial($editorial)
    {
        $this->editorial = $editorial;
    
        return $this;
    }

    /**
     * Get editorial
     *
     * @return string 
     */
    public function getEditorial()
    {
        return $this->editorial;
    }

    /**
     * Set datePublication
     *
     * @param \DateTime $datePublication
     * @return Newsletter
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
     * Set statut
     *
     * @param string $statut
     * @return Newsletter
     */
    public function setStatut($statut)
    {
        $this->statut = $statut;
    
        return $this;
    }

    /**
     * Get statut
     *
     * @return string 
     */
    public function getStatut()
    {
        return $this->statut;
    }

    /**
     * Set envoyes
     *
     * @param string $envoyes
     * @return Newsletter
     */
    public function setEnvoyes($envoyes)
    {
        $this->envoyes = $envoyes;
    
        return $this;
    }

    /**
     * Get envoyes
     *
     * @return string 
     */
    public function getEnvoyes()
    {
        return $this->envoyes;
    }

    /**
     * Set AstroLoveBélier
     *
     * @param string $astroLoveBélier
     * @return Newsletter
     */
    public function setAstroLoveBelier($astroLoveBelier)
    {
        $this->AstroLoveBelier = $astroLoveBelier;
    
        return $this;
    }

    /**
     * Get AstroLoveBélier
     *
     * @return string 
     */
    public function getAstroLoveBelier()
    {
        return $this->AstroLoveBelier;
    }

    /**
     * Set AstroLoveTaureau
     *
     * @param string $astroLoveTaureau
     * @return Newsletter
     */
    public function setAstroLoveTaureau($astroLoveTaureau)
    {
        $this->AstroLoveTaureau = $astroLoveTaureau;
    
        return $this;
    }

    /**
     * Get AstroLoveTaureau
     *
     * @return string 
     */
    public function getAstroLoveTaureau()
    {
        return $this->AstroLoveTaureau;
    }

    /**
     * Set AstroLoveGemeaux
     *
     * @param string $astroLoveGemeaux
     * @return Newsletter
     */
    public function setAstroLoveGemeaux($astroLoveGemeaux)
    {
        $this->AstroLoveGemeaux = $astroLoveGemeaux;
    
        return $this;
    }

    /**
     * Get AstroLoveGemeaux
     *
     * @return string 
     */
    public function getAstroLoveGemeaux()
    {
        return $this->AstroLoveGemeaux;
    }

    /**
     * Set AstroLoveCancer
     *
     * @param string $astroLoveCancer
     * @return Newsletter
     */
    public function setAstroLoveCancer($astroLoveCancer)
    {
        $this->AstroLoveCancer = $astroLoveCancer;
    
        return $this;
    }

    /**
     * Get AstroLoveCancer
     *
     * @return string 
     */
    public function getAstroLoveCancer()
    {
        return $this->AstroLoveCancer;
    }

    /**
     * Set AstroLoveLion
     *
     * @param string $astroLoveLion
     * @return Newsletter
     */
    public function setAstroLoveLion($astroLoveLion)
    {
        $this->AstroLoveLion = $astroLoveLion;
    
        return $this;
    }

    /**
     * Get AstroLoveLion
     *
     * @return string 
     */
    public function getAstroLoveLion()
    {
        return $this->AstroLoveLion;
    }

    /**
     * Set AstroLoveVierge
     *
     * @param string $astroLoveVierge
     * @return Newsletter
     */
    public function setAstroLoveVierge($astroLoveVierge)
    {
        $this->AstroLoveVierge = $astroLoveVierge;
    
        return $this;
    }

    /**
     * Get AstroLoveVierge
     *
     * @return string 
     */
    public function getAstroLoveVierge()
    {
        return $this->AstroLoveVierge;
    }

    /**
     * Set AstroLoveBalance
     *
     * @param string $astroLoveBalance
     * @return Newsletter
     */
    public function setAstroLoveBalance($astroLoveBalance)
    {
        $this->AstroLoveBalance = $astroLoveBalance;
    
        return $this;
    }

    /**
     * Get AstroLoveBalance
     *
     * @return string 
     */
    public function getAstroLoveBalance()
    {
        return $this->AstroLoveBalance;
    }

    /**
     * Set AstroLoveScorpion
     *
     * @param string $astroLoveScorpion
     * @return Newsletter
     */
    public function setAstroLoveScorpion($astroLoveScorpion)
    {
        $this->AstroLoveScorpion = $astroLoveScorpion;
    
        return $this;
    }

    /**
     * Get AstroLoveScorpion
     *
     * @return string 
     */
    public function getAstroLoveScorpion()
    {
        return $this->AstroLoveScorpion;
    }

    /**
     * Set AstroLoveSagittaire
     *
     * @param string $astroLoveSagittaire
     * @return Newsletter
     */
    public function setAstroLoveSagittaire($astroLoveSagittaire)
    {
        $this->AstroLoveSagittaire = $astroLoveSagittaire;
    
        return $this;
    }

    /**
     * Get AstroLoveSagittaire
     *
     * @return string 
     */
    public function getAstroLoveSagittaire()
    {
        return $this->AstroLoveSagittaire;
    }

    /**
     * Set AstroLoveCapricorne
     *
     * @param string $astroLoveCapricorne
     * @return Newsletter
     */
    public function setAstroLoveCapricorne($astroLoveCapricorne)
    {
        $this->AstroLoveCapricorne = $astroLoveCapricorne;
    
        return $this;
    }

    /**
     * Get AstroLoveCapricorne
     *
     * @return string 
     */
    public function getAstroLoveCapricorne()
    {
        return $this->AstroLoveCapricorne;
    }

    /**
     * Set AstroLoveVerseau
     *
     * @param string $astroLoveVerseau
     * @return Newsletter
     */
    public function setAstroLoveVerseau($astroLoveVerseau)
    {
        $this->AstroLoveVerseau = $astroLoveVerseau;
    
        return $this;
    }

    /**
     * Get AstroLoveVerseau
     *
     * @return string 
     */
    public function getAstroLoveVerseau()
    {
        return $this->AstroLoveVerseau;
    }

    /**
     * Set AstroLovePoissons
     *
     * @param string $astroLovePoissons
     * @return Newsletter
     */
    public function setAstroLovePoissons($astroLovePoissons)
    {
        $this->AstroLovePoissons = $astroLovePoissons;
    
        return $this;
    }

    /**
     * Get AstroLovePoissons
     *
     * @return string 
     */
    public function getAstroLovePoissons()
    {
        return $this->AstroLovePoissons;
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
     * Set lnBonPlan
     *
     * @param TDN\Bundle\RedactionBundle\Entity\Article $lnBonPlan
     * @return Newsletter
     */
    public function setLnBonPlan(\TDN\Bundle\RedactionBundle\Entity\Article $lnBonPlan = null)
    {
        $this->lnBonPlan = $lnBonPlan;
    
        return $this;
    }

    /**
     * Get lnBonPlan
     *
     * @return TDN\Bundle\RedactionBundle\Entity\Article 
     */
    public function getLnBonPlan()
    {
        return $this->lnBonPlan;
    }

    /**
     * Set lnVideo
     *
     * @param TDN\Bundle\VideoBundle\Entity\Video $lnVideo
     * @return Newsletter
     */
    public function setLnVideo(\TDN\Bundle\VideoBundle\Entity\Video $lnVideo = null)
    {
        $this->lnVideo = $lnVideo;
    
        return $this;
    }

    /**
     * Get lnVideo
     *
     * @return TDN\Bundle\VideoBundle\Entity\Video 
     */
    public function getLnVideo()
    {
        return $this->lnVideo;
    }

    /**
     * Set lnAuteur
     *
     * @param TDN\Bundle\NanaBundle\Entity\Nana $lnAuteur
     * @return Newsletter
     */
    public function setLnAuteur(\TDN\Bundle\NanaBundle\Entity\Nana $lnAuteur = null)
    {
        $this->lnAuteur = $lnAuteur;
    
        return $this;
    }

    /**
     * Get lnAuteur
     *
     * @return TDN\Bundle\NanaBundle\Entity\Nana 
     */
    public function getLnAuteur()
    {
        return $this->lnAuteur;
    }

    /**
     * Set lnArticleSponsor
     *
     * @param TDN\Bundle\RedactionBundle\Entity\Article $lnArticleSponsor
     * @return Newsletter
     */
    public function setLnArticleSponsor(\TDN\Bundle\RedactionBundle\Entity\Article $lnArticleSponsor = null)
    {
        $this->lnArticleSponsor = $lnArticleSponsor;
    
        return $this;
    }

    /**
     * Get lnArticleSponsor
     *
     * @return TDN\Bundle\RedactionBundle\Entity\Article 
     */
    public function getLnArticleSponsor()
    {
        return $this->lnArticleSponsor;
    }

    /**
     * Set lnLire
     *
     * @param TDN\Bundle\DocumentBundle\Entity\Document $lnLire
     * @return Newsletter
     */
    public function setLnLire(\TDN\Bundle\DocumentBundle\Entity\Document $lnLire = null)
    {
        $this->lnLire = $lnLire;
    
        return $this;
    }

    /**
     * Get lnLire
     *
     * @return TDN\Bundle\DocumentBundle\Entity\Document 
     */
    public function getLnLire()
    {
        return $this->lnLire;
    }

    /**
     * Set lnQuestion
     *
     * @param TDN\Bundle\CauseuseBundle\Entity\Question $lnQuestion
     * @return Newsletter
     */
    public function setLnQuestion(\TDN\Bundle\CauseuseBundle\Entity\Question $lnQuestion = null)
    {
        $this->lnQuestion = $lnQuestion;
    
        return $this;
    }

    /**
     * Get lnQuestion
     *
     * @return TDN\Bundle\CauseuseBundle\Entity\Question 
     */
    public function getLnQuestion()
    {
        return $this->lnQuestion;
    }
}
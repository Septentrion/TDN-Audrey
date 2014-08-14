<?php

namespace TDN\Bundle\CommentaireBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TDN\Bundle\CommentaireBundle\Entity\Commentaire
 */
class Commentaire
{
    /**
     * @var integer $idAuteur
     */
    private $idAuteur;

    /**
     * @var integer $typeContenu
     */
    private $typeContenu;

    /**
     * @var integer $idContenu
     */
    private $idContenu;

    /**
     * @var integer $idThread
     */
    private $idThread;

    /**
     * @var integer $idReponse
     */
    private $idReponse;

    /**
     * @var string $texteCommentaire
     */
    private $texteCommentaire;

    /**
     * @var integer $like
     */
    private $like;

    /**
     * @var boolean $abonne
     */
    private $abonne;

    /**
     * @var integer $statut
     */
    private $statut;

    /**
     * @var \DateTime $datePublication
     */
    private $datePublication;

    /**
     * @var integer $idCommentaire
     */
    private $idCommentaire;

    /**
     * @var integer $filDocument
     */
    private $filDocument;

    /**
     * @var integer $filAuteur
     */
    private $filAuteur;

    /**
     * @var integer $v2ID
     */
    private $v2ID;


    /**
     * Set idAuteur
     *
     * @param integer $idAuteur
     * @return Commentaire
     */
    public function setIdAuteur($idAuteur)
    {
        $this->idAuteur = $idAuteur;
    
        return $this;
    }

    /**
     * Get idAuteur
     *
     * @return integer 
     */
    public function getIdAuteur()
    {
        return $this->idAuteur;
    }

    /**
     * Set typeContenu
     *
     * @param integer $typeContenu
     * @return Commentaire
     */
    public function setTypeContenu($typeContenu)
    {
        $this->typeContenu = $typeContenu;
    
        return $this;
    }

    /**
     * Get typeContenu
     *
     * @return integer 
     */
    public function getTypeContenu()
    {
        return $this->typeContenu;
    }

    /**
     * Set idContenu
     *
     * @param integer $idContenu
     * @return Commentaire
     */
    public function setIdContenu($idContenu)
    {
        $this->idContenu = $idContenu;
    
        return $this;
    }

    /**
     * Get idContenu
     *
     * @return integer 
     */
    public function getIdContenu()
    {
        return $this->idContenu;
    }

    /**
     * Set idThread
     *
     * @param integer $idThread
     * @return Commentaire
     */
    public function setIdThread($idThread)
    {
        $this->idThread = $idThread;
    
        return $this;
    }

    /**
     * Get idThread
     *
     * @return integer 
     */
    public function getIdThread()
    {
        return $this->idThread;
    }

    /**
     * Set idReponse
     *
     * @param integer $idReponse
     * @return Commentaire
     */
    public function setIdReponse($idReponse)
    {
        $this->idReponse = $idReponse;
    
        return $this;
    }

    /**
     * Get idReponse
     *
     * @return integer 
     */
    public function getIdReponse()
    {
        return $this->idReponse;
    }

    /**
     * Set texteCommentaire
     *
     * @param string $texteCommentaire
     * @return Commentaire
     */
    public function setTexteCommentaire($texteCommentaire)
    {
        $this->texteCommentaire = $texteCommentaire;
    
        return $this;
    }

    /**
     * Get texteCommentaire
     *
     * @return string 
     */
    public function getTexteCommentaire()
    {
        return $this->texteCommentaire;
    }

    /**
     * Set like
     *
     * @param integer $like
     * @return Commentaire
     */
    public function setLike($like)
    {
        $this->like = $like;
    
        return $this;
    }

    /**
     * Get like
     *
     * @return integer 
     */
    public function getLike()
    {
        return $this->like;
    }

    /**
     * Set abonne
     *
     * @param boolean $abonne
     * @return Commentaire
     */
    public function setAbonne($abonne)
    {
        $this->abonne = $abonne;
    
        return $this;
    }

    /**
     * Get abonne
     *
     * @return boolean 
     */
    public function getAbonne()
    {
        return $this->abonne;
    }

    /**
     * Set statut
     *
     * @param integer $statut
     * @return Commentaire
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
     * @return Commentaire
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
     * Get idCommentaire
     *
     * @return integer 
     */
    public function getIdCommentaire()
    {
        return $this->idCommentaire;
    }
    /**
     * @var TDN\Bundle\DocumentBundle\Entity\Document
     */
    private $filCmmentaires;


    /**
     * Set filCmmentaires
     *
     * @param TDN\Bundle\DocumentBundle\Entity\Document $filCmmentaires
     * @return Commentaire
     */
    public function setFilCmmentaires(\TDN\Bundle\DocumentBundle\Entity\Document $filCmmentaires = null)
    {
        $this->filCmmentaires = $filCmmentaires;
    
        return $this;
    }

    /**
     * Get filCmmentaires
     *
     * @return TDN\Bundle\DocumentBundle\Entity\Document 
     */
    public function getFilCmmentaires()
    {
        return $this->filCmmentaires;
    }

        /**
     * Set v2ID
     *
     * @param integer $v2ID
     * @return Commentaire
     */
    public function setV2ID($v2ID)
    {
        $this->v2ID = $v2ID;
    
        return $this;
    }

    /**
     * Get v2ID
     *
     * @return integer 
     */
    public function getV2ID()
    {
        return $this->v2ID;
    }

    /**
     * Set filAuteur
     *
     * @param TDN\Bundle\NanaBundle\Entity\Nana $filAuteur
     * @return Nana
     */
    public function setFilAuteur(\TDN\Bundle\NanaBundle\Entity\Nana $filAuteur = null)
    {
        $this->filAuteur = $filAuteur;
    
        return $this;
    }

    /**
     * Get filAuteur
     *
     * @return TDN\Bundle\NanaBundle\Entity\Nana $filAuteur
     */
    public function getFilAuteur()
    {
        return $this->filAuteur;
    }

    /**
     * Set filDocument
     *
     * @param TDN\Bundle\DocumentBundle\Entity\Document $filDocument
     * @return Document
     */
    public function setFilDocument(\TDN\Bundle\DocumentBundle\Entity\Document $filDocument = null)
    {
        $this->filDocument = $filDocument;
    
        return $this;
    }

    /**
     * Get filDocument
     *
     * @return TDN\Bundle\DocumentBundle\Entity\Document $filDocument
     */
    public function getFilDocument()
    {
        return $this->filDocument;
    }
    /**
     * @var string $codePromoTDN
     */



}
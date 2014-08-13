<?php

namespace TDN\DocumentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TDN\DocumentBundle\Entity\Tag
 */
class Tag
{
    /**
     * @var string $lemme
     */
    private $lemme;

    /**
     * @var string $forme
     */
    private $forme;

    /**
     * @var integer $id
     */
    private $id;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    private $filDocuments;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->setDocument = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Set lemme
     *
     * @param string $lemme
     * @return Tag
     */
    public function setLemme($lemme)
    {
        $this->lemme = $lemme;
    
        return $this;
    }

    /**
     * Get lemme
     *
     * @return string 
     */
    public function getLemme()
    {
        return $this->lemme;
    }

    /**
     * Set forme
     *
     * @param string $forme
     * @return Tag
     */
    public function setForme($forme)
    {
        $this->forme = $forme;
    
        return $this;
    }

    /**
     * Get forme
     *
     * @return string 
     */
    public function getForme()
    {
        return $this->forme;
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
     * Add setDocument
     *
     * @param TDN\DocumentBundle\Entity\Document $filDocument
     * @return Tag
     */
    public function addFilDocument(\TDN\DocumentBundle\Entity\Document $filDocument)
    {
        $this->filDocuments[] = $filDocument;
    
        return $this;
    }

    /**
     * Remove setDocument
     *
     * @param TDN\DocumentBundle\Entity\Document $filDocument
     */
    public function removeFilDocument(\TDN\DocumentBundle\Entity\Document $filDocument)
    {
        $this->filDocuments->removeElement($filDocument);
    }

    /**
     * Get setDocument
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getFilDocument()
    {
        return $this->filDocuments;
    }
    /**
     * @var string $expression
     */
    private $expression;


    /**
     * Set expression
     *
     * @param string $expression
     * @return Tag
     */
    public function setExpression($expression)
    {
        $this->expression = $expression;
    
        return $this;
    }

    /**
     * Get expression
     *
     * @return string 
     */
    public function getExpression()
    {
        return $this->expression;
    }

    /**
     * Get filDocuments
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getFilDocuments()
    {
        return $this->filDocuments;
    }
}
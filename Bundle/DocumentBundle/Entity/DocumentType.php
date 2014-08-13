<?php

namespace TDN\Bundle\DocumentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TDN\Bundle\DocumentBundle\Entity\DocumentType
 */
class DocumentType
{
    /**
     * @var string $type
     */
    private $type;

    /**
     * @var string $abstract
     */
    private $abstract;

    /**
     * @var integer $idType
     */
    private $idType;


    /**
     * Set type
     *
     * @param string $type
     * @return DocumentType
     */
    public function setType($type)
    {
        $this->type = $type;
    
        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set abstract
     *
     * @param string $abstract
     * @return DocumentType
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
     * Get idType
     *
     * @return integer 
     */
    public function getIdType()
    {
        return $this->idType;
    }
}
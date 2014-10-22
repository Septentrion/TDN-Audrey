<?php

namespace TDN\Bundle\ImageBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use TDN\Bundle\DocumentBundle\Entity\Document;

/**
 * TDN\Bundle\ImageBundle\Entity\Image
 */
class Image extends Document {

    /**
     * @var string $fichier
     */
    private $fichier;

    /**
     * @var string $alt
     */
    private $alt;

    /**
     * @var string $mimeType
     */
    private $mimeType;

    /**
     * @var TDN\Bundle\NanaBundle\Entity\Nana
     */
    private $idOwner;

    /**
     * @Assert\File(maxSize="6000000")
     */
    public $upload;

    /**
     * Set fichier
     *
     * @param string $fichier
     * @return Image
     */
    public function setFichier($fichier)
    {
        $this->fichier = $fichier;
    
        return $this;
    }

    /**
     * Get fichier
     *
     * @return string 
     */
    public function getFichier()
    {
        return $this->fichier;
    }

    /**
     * Set alt
     *
     * @param string $alt
     * @return Image
     */
    public function setAlt($alt)
    {
        $this->alt = $alt;
    
        return $this;
    }

    /**
     * Get alt
     *
     * @return string 
     */
    public function getAlt()
    {
        return $this->alt;
    }

    /**
     * Set mimeType
     *
     * @param string $mimeType
     * @return Image
     */
    public function setMimeType($mimeType)
    {
        $this->mimeType = $mimeType;
    
        return $this;
    }

    /**
     * Get mimeType
     *
     * @return string 
     */
    public function getMimeType()
    {
        return $this->mimeType;
    }

    /**
     * Set idOwner
     *
     * @param TDN\Bundle\NanaBundle\Entity\Nana $idOwner
     * @return Image
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

    public function storeUpload() {

        // the file property can be empty if the field is not required
        if (null === $this->upload) {
            return;
        }

        // set the path property to the filename where you've saved the file
        $this->fichier = $this->upload->getClientOriginalName();
        $this->setMimeType($this->upload->getMimeType());

        // use the original file name here but you should
        // sanitize it at least to avoid any security issues

        // move takes the target directory and then the
        // target filename to move to
        $this->upload->move(
            $this->getUploadRootDir(),
            $this->upload->getClientOriginalName()
        );

        // clean up the file property as you won't need it anymore
        $this->upload = null;
    }

    public function storeUploadCustom($path) {

        // the file property can be empty if the field is not required
        if (null === $this->upload) {
            return;
        }

        // set the path property to the filename where you've saved the file
        $this->fichier = $this->upload->getClientOriginalName();
        $this->setMimeType($this->upload->getMimeType());

        // use the original file name here but you should
        // sanitize it at least to avoid any security issues

        // move takes the target directory and then the
        // target filename to move to
        $this->upload->move(
            $this->getUploadRootDir().$path,
            $this->upload->getClientOriginalName()
        );

        // clean up the file property as you won't need it anymore
        $this->upload = null;
    }

    public function isUpdated() {
        return (null !== $this->upload);
    }

    public function getAbsolutePath()
    {
        return null === $this->fichier
            ? null
            : $this->getUploadRootDir().'/'.$this->fichier;
    }

    public function getWebPath()
    {
        return null === $this->fichier
            ? null
            : $this->getUploadDir().'/'.$this->fichier;
    }

    protected function getUploadRootDir()
    {
        // the absolute directory path where uploaded
        // documents should be saved
        return $_SERVER['DOCUMENT_ROOT'].$this->getUploadDir();
    }

    protected function getUploadDir()
    {
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return 'uploads/documents';
    }

    public function init ($owner = NULL, $dossier) {
        $titre = $this->getTitre();
        if (is_null($titre)) {
            $this->setTitre('Image sans titre');
        }
        $this->setIdOwner($owner);
        $this->setlnAuteur($owner);
        $this->storeUploadCustom($dossier);
        $this->setLikes(0);
        $this->setHits(0);
        $this->makeSlug();
        $this->setStatut("IMAGE_PUBLIQUE");
        $this->setVersion("1");
        $this->setAlt($this->getTitre());
        $this->setTags("image");
        $this->setDatePublication(new \DateTime);
        $this->setDateModification(new \DateTime);
        $this->setCommentThread(new \Doctrine\Common\Collections\ArrayCollection());
        return $this;
    }

    public function scale ($width, $height, $fit) {
        $fileImage = $this->getAbsolutePath();
// print_r($fileImage);
        $processImage = new \Imagick($fileImage);
        $processImage->scaleImage($width, $height, $fit);
        $processImage->writeImage($fileImage);
        $processImage->destroy();
        return $this;
    }
}

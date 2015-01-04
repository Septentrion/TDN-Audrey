<?php

namespace TDN\Bundle\ImageBundle\Processing;

use TDN\Bundle\ImageBundle\Entity\Image;

use TDN\Bundle\NanaBundle\Entity\Nana;
use TDN\Bundle\NanaBundle\Entity\NanaRoles;

class ImageProcessor {

    const USER_DOCUMENT =  "/public/%s/%s/n_/%d/";
    const REDACTION_DOCUMENT =  "/public/%s/%s/";
    const USER_PRIVE =  "/public/profils/%d/";

	private $root;

	public function __construct ($root) {

		$this->media_root = $root;
	}

	private function selectionDossier ($tag = 'docs', $nana) {
        $now = new \DateTime;
        $isUser = $nana->isA('user');

        if ($tag === 'docs') {
            $squelette = $isUser ? self::USER_DOCUMENT : self::REDACTION_DOCUMENT;
            $dossier = sprintf($squelette,$now->format('Y'),$now->format('m'),$nana->getIdNana());            
        } else {
	            $squelette = self::USER_PRIVE;
	            $dossier = sprintf($squelette,$nana->getIdNana());            
        }

        return $dossier;
	}

	public function make (Image $image, Nana $nana, $type = 'docs', array $formats) {
		$dossier = $this->selectionDossier($type, $nana);
		$image->init($nana, $dossier);
        $fichierImage = $image->getFichier();
        $source = '/'.$this->media_root.$dossier.$fichierImage;
		foreach ($formats as $contrainte => $taille) {
        	switch ($contrainte) {
        		case 'portrait':
        			switch ($taille) {
        				default :
        					$hauteur = (intval($taille) > 0) ? intval($taille) : 500;
        					$prefixe = 'y'.$hauteur.'_';
        			}
			        $err = $imageProcessor->downScale($source, $hauteur, 'height', $prefixe);
        			break;
        		
        		case 'paysage':
        			switch ($taille) {
        				case 'slider' :
        					$largeur = 700;
        					$prefixe = 'x7_';
        					break;

        				default :
        					$largeur = ((intval($taille) > 0) ? intval($taille) : 500);
        					$prefixe = 'x'.$largeur.'_';
        			}
			        $err = $imageProcessor->downScale($source, $largeur, 'width', $prefixe);
        			break;

        		case 'square':
        		default:
        			$cote = ($taille !== 'default') ? intval($taille): 300;
        			$err = $this->square($source, $cote, '$sqr'.(($taille !== 'default') ? intval($taille): '').'_' );
    		}
        }
	}


	public function downScale($source, $taille, $sens = 'height', $prefixe = 'x7_')
	{
		$source = $_SERVER['DOCUMENT_ROOT'].$source;
		if (is_file($source)) {
			$imSource = new \Imagick($source);
            $geo = $imSource->getImageGeometry();
 	        if (!is_null($prefixe)) {
	        	$path = explode('/', $source);
	        	$fichier = $prefixe.array_pop($path);
	        	array_push($path, $fichier);
	        	$cible = implode('/', $path);
	        } else {
	        	$cible = $source;
	        }
			$imSource->stripImage();
           if (($sens == 'height') && ($geo['height'] > $taille)) {
                $imSource->scaleImage(0,$taille);
            } elseif (($sens == 'width') && ($geo['width'] > $taille)) {
            	$imSource->scaleImage($taille,0);
            } 
	        $this->normalizeJPEG($imSource);
        	$imSource->writeImage($cible);
            $imSource->destroy();           

            return true;
		} else {
			return false;
		}
	}

	 public function square ($fichier, $cote, $prefixe = NULL) {

		$source = $_SERVER['DOCUMENT_ROOT'].$fichier;
		if (is_file($source)) {
	        $imSource = new \Imagick($source);
	        $geo = $imSource->getImageGeometry();
	        if ($geo['width'] > $geo['height']) {
	            $imSource->scaleImage(0, $cote);
	            $geoS = $imSource->getImageGeometry();
	            $offsetX = floor(($geoS['width'] - $cote)/2);
	            $offsetY = 0;
	        } else {
	            $imSource->scaleImage($cote,0);
	            $geoS = $imSource->getImageGeometry();
	            $offsetX = 0;
	            $offsetY = floor(($geoS['height'] - $cote)/2);
	        }
	        $imSource->cropImage($cote, $cote, $offsetX, $offsetY);
	        if (!is_null($prefixe)) {
	        	$path = explode('/', $source);
	        	$fichier = $prefixe.array_pop($path);
	        	array_push($path, $fichier);
	        	$cible = implode('/', $path);
	        } else {
	        	$cible = $source;
	        }
	        $this->normalizeJPEG($imSource);
	        $imSource->writeImage($cible);
	        $imSource->destroy();  

	        return true;                             
		} else {
			return false;
		}
    }

    private function normalizeJPEG (\Imagick $source) {
        $source->setImageFormat('jpeg');
	    $source->setImageCompression(\Imagick::COMPRESSION_JPEG);
	    $source->setImageCompressionQuality(70);
    	$source->setInterlaceScheme(\Imagick::INTERLACE_PLANE);
	    $source->stripImage();
    }

}
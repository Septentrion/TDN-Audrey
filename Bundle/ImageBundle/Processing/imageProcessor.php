<?php

namespace TDN\Bundle\ImageBundle\Processing;
 
class imageProcessor {

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
            $imSource->setImageFormat('jpeg');
            $imSource->setCompression(Imagick::COMPRESSION_JPEG);
			$imSource->setCompressionQuality(80);
        	$imSource->setInterlaceScheme(Imagick::INTERLACE_PLANE);
        	$imSource->stripImage();
        	$imSource->writeImage($cible);
            $imSource->destroy();           

            return true;
		} else {
			return false;
		}
	}

	 public function square ($fichier, $cote, $prefixe = NULL) 
    {
		$source = $_SERVER['DOCUMENT_ROOT'].$fichier;
		if (is_file($source)) {
	        $squared = new \Imagick($source);
	        $geo = $squared->getImageGeometry();
	        if ($geo['width'] > $geo['height']) {
	            $squared->scaleImage(0, $cote);
	            $geoS = $squared->getImageGeometry();
	            $offsetX = floor(($geoS['width'] - $cote)/2);
	            $offsetY = 0;
	        } else {
	            $squared->scaleImage($cote,0);
	            $geoS = $squared->getImageGeometry();
	            $offsetX = 0;
	            $offsetY = floor(($geoS['height'] - $cote)/2);
	        }
	        $squared->cropImage($cote, $cote, $offsetX, $offsetY);
	        if (!is_null($prefixe)) {
	        	$path = explode('/', $source);
	        	$fichier = $prefixe.array_pop($path);
	        	array_push($path, $fichier);
	        	$cible = implode('/', $path);
	        } else {
	        	$cible = $source;
	        }
	        $squared->stripImage();
		    $squared->setImageCompression(\Imagick::COMPRESSION_JPEG);
		    $squared->setImageCompressionQuality(70);
		    $squared->stripImage();
	        $squared->writeImage($cible);
	        $squared->destroy();  

	        return true;                             
		} else {
			return false;
		}
    }

}
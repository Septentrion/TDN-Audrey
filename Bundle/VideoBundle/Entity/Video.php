<?php

namespace TDN\Bundle\VideoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use TDN\Bundle\DocumentBundle\Entity\Document;

/**
 * TDN\Bundle\VideoBundle\Entity\Video
 */
class Video extends Document {

    private $_hebergeursWithAPI = array('dailymotion', 'youtube', 'vimeo');


    /**
     * @var string $idHebergeur
     */
    private $idHebergeur;

    /**
     * @var string $idVideo
     */
    private $idVideo;

    /**
     * @var string $codeIntegration
     */
    private $codeIntegration;

    /**
     * @var string $vignette
     */
    private $vignette;

    /**
     * @var string $duree
     */
    private $duree;


    /**
     * @var string $params
     */
    private $params;


    /**
     * Set idHebergeur
     *
     * @param string $idHebergeur
     * @return Video
     */
    public function setIdHebergeur($idHebergeur)
    {
        $this->idHebergeur = $idHebergeur;
    
        return $this;
    }

    /**
     * Get idHebergeur
     *
     * @return string 
     */
    public function getIdHebergeur()
    {
        return $this->idHebergeur;
    }

    /**
     * Set idVideo
     *
     * @param string $idVideo
     * @return Video
     */
    public function setIdVideo($idVideo)
    {
        $this->idVideo = $idVideo;
    
        return $this;
    }

    /**
     * Get idVideo
     *
     * @return string 
     */
    public function getIdVideo()
    {
        return $this->idVideo;
    }

    /**
     * Set codeIntegration
     *
     * @param string $codeIntegration
     * @return Video
     */
    public function setCodeIntegration($codeIntegration)
    {
        $this->codeIntegration = $codeIntegration;
    
        return $this;
    }

    /**
     * Get codeIntegration
     *
     * @return string 
     */
    public function getCodeIntegration()
    {
        return $this->codeIntegration;
    }

    /**
     * Set duree
     *
     * @param string $duree
     * @return Video
     */
    public function setDuree($duree)
    {
        $this->duree = $duree;
    
        return $this;
    }

    /**
     * Get duree
     *
     * @return string 
     */
    public function getDuree()
    {
        return $this->duree;
    }

    /**
     * Set vignette
     *
     * @param string $vignette
     * @return Video
     */
    public function setVignette($vignette)
    {
        $this->vignette = $vignette;
    
        return $this;
    }

    /**
     * Get vignette
     *
     * @return string 
     */
    public function getVignette()
    {
        return $this->vignette;
    }

    /**
     * Set params
     *
     * @param string $params
     * @return Video
     */
    public function setParams($params)
    {
        $this->params = $params;
    
        return $this;
    }

    /**
     * Get params
     *
     * @return string 
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * Import vidéo from Vimeo
     *
     * @return string 
     */
    public function vimeoImport ($idVideo) {
        $sourceURL = "http://vimeo.com/api/v2/video/$idVideo.xml";
        $channel = curl_init();
        curl_setopt($channel, CURLOPT_URL, $sourceURL);
        curl_setopt($channel, CURLOPT_HEADER, 0);
        curl_setopt($channel, CURLOPT_RETURNTRANSFER, 1);
        $squelette = curl_exec($channel);
        curl_close($channel);
        libxml_use_internal_errors(true);
        $squelette = simplexml_load_string($squelette,'SimpleXmlElement',LIBXML_NOERROR+LIBXML_ERR_FATAL+LIBXML_ERR_NONE);
        $errors = libxml_get_errors();
        if (empty($errors)) {
            return $squelette;
        } else {
            return false;
        }

        return false;
    }

    public function vimeoBuildParameters ($squelette) {
        $this->setIdHebergeur('vimeo');
        $vimeoPattern = '<iframe src="http://player.vimeo.com/video/%s?badge=0" width="%s" height="%s" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe><p><a href="http://vimeo.com/%s">%s</a> from <a href="%s">%s</a> on <a href="http://vimeo.com">Vimeo</a>.</p>';
        $_height = $squelette->video->height * (400 / $squelette->video->width);
        $this->setIdVideo((string)$squelette->video->id);
        $this->setTitre((string)$squelette->video->title);
        $this->setCodeIntegration(
            sprintf($vimeoPattern,
                (string)$squelette->video->id,
                '400px',
                (string)$_height.'px',
                (string)$squelette->video->id,
                (string)$squelette->video->title,
                (string)$squelette->video->user_id,
                (string)$squelette->video->user_name
            ));
        $this->setVignette((string)$squelette->video->thumbnail_medium);
        $duree = "";
        $dureeData = (integer)$squelette->video->duration;
        $heures = floor($dureeData / 3600);
        if ($heures > 0) $duree .= $heures.'h ';
        $rest = $dureeData - ($heures * 3600);
        $mins = floor($rest / 60);
        if ($mins > 0) $duree .= $mins.'min ';
        $secs = $rest - ($mins * 60);
        if ($secs > 0) $duree .= $secs.'sec ';
        $this->setDuree($duree);
        $params['user_name'] = (string)$squelette->video->user_name;
        $params['user_url'] = (string)stripcslashes($squelette->video->user_url);
        $params['width'] = (integer)$squelette->video->width;
        $params['height'] = (integer)$squelette->video->height;
        $this->setParams(json_encode($params));
    }

    /**
     * Import vidéo from DailyMotion
     *
     * @return string 
     */
    public function dailymotionImport ($segment) {
        $_pattern = "/(video\/)?([^\?\#]+)/";
        $segmentElements = preg_match($_pattern, $segment, $matches);
        $idVideo = $matches[2];
        $channel = curl_init();
        $sourceURL = "https://api.dailymotion.com/video/$idVideo?fields=";
        $fields = array('id', 'duration', 'embed_html', 'thumbnail_medium_url', 'title', 'url', 'owner', 'owner.screenname');
        $sourceURL .= implode(',', $fields);
        curl_setopt($channel, CURLOPT_URL, $sourceURL);
        curl_setopt($channel, CURLOPT_HEADER, 0);
        curl_setopt($channel, CURLOPT_RETURNTRANSFER, 1);
        $squelette = curl_exec($channel);
        curl_close($channel);
        return json_decode($squelette);
    }

    public function dailymotionBuildParameters ($squelette) {
        $this->setIdHebergeur('dailymotion');
        // print_r($squelette); die;
        $this->setIdVideo($squelette->id);
        $this->setTitre($squelette->title);
        $this->setcodeIntegration($squelette->embed_html);
        $this->setDuree($squelette->duration);
        $this->setVignette($squelette->thumbnail_medium_url);
        $params['url'] = $squelette->url;
        $params['owner'] = $squelette->owner;
        $_os = "owner.screenname";
        $params['owner.screenname'] = $squelette->$_os;
        $this->setParams(json_encode($params));

    }
    
    /**
     * Import vidéo from Youtube
     *
     * @return string 
     */
    public function youtubeImport ($segment) {
        // $_pattern = "/watch\?(v=)?([^\?\#\&]+)/";
        $_pattern = "/(watch\?)?([a-z]+=[^\?\#\&]+)[\?\#\&]?/";
        $segmentElements = preg_match_all($_pattern, $segment, $matches);
        foreach($matches[2] as $parametre) {
            $isIdVideo = preg_match("/v=(.+)/", $parametre, $matches);
            if (!empty($matches[1])) {
                $idVideo = $matches[1];
                break;
            }
        }

        if (empty($idVideo)) {
             $_pattern = "/([^\?\#\&]+)/";
            $segmentElements = preg_match($_pattern, $segment, $matches);
            if (!empty($matches)) {
                $idVideo = $matches[1];
            } else {
                $idVideo = 'none';
            }
        }
        $sourceURL = "https://www.googleapis.com/youtube/v3/videos?id=".$idVideo;
        $sourceURL .= "&key=AIzaSyB9LOlXIBAVnYGxQOmzyJSHu3UgWMmE4Bk&part=contentDetails,snippet,statistics,status";
        $channel = curl_init();
        curl_setopt($channel, CURLOPT_URL, $sourceURL);
        curl_setopt($channel, CURLOPT_HEADER, 0);
        curl_setopt($channel, CURLOPT_RETURNTRANSFER, 1);
        $squelette = curl_exec($channel);
        curl_close($channel);
        return json_decode($squelette);
    }

    public function youtubeBuildParameters ($squelette) {
        $this->setIdHebergeur('youtube');
        $youtubePattern = '<iframe width="%s" height="%s" src="http://www.youtube.com/embed/%s" frameborder="0" allowfullscreen></iframe>';
        $timePattern = "/[A-Z]+((\d+)M)*((\d+)S)*/";
        // print_r($squelette); die;
        $this->setIdVideo($squelette->id);
        $itre = $this->getTitre();
        if (empty($titre)) {
            $this->setTitre($squelette->snippet->title);
        }
        $this->setCodeIntegration(
            sprintf($youtubePattern,
                 '420px',
                 '315px',
                 $squelette->id
            ));
        $this->setVignette($squelette->snippet->thumbnails->medium->url);
        $_yt = preg_match($timePattern, $squelette->contentDetails->duration, $matches);
        // $_yt = preg_match($timePattern, 'PT2M59S', $matches);
        // print_r($matches); print_r($squelette->contentDetails->duration);
        $_mins = count($matches) >= 2 ? (integer)$matches[2] : 0;
        $_secs = count($matches) >= 4 ? (integer)$matches[4] : 0;
        $this->setDuree($_mins.'min '.$_secs.'sec');
        $params['channelId'] = $squelette->snippet->channelId;
        $params['channelTitle'] = $squelette->snippet->channelTitle;
        $params['dimension'] = $squelette->contentDetails->dimension;
        $params['duration'] = $squelette->contentDetails->duration;
        $this->setParams(json_encode($params));
        return $squelette;
    }

    public function videoSegment ($segment) {
        switch ($this->getIdHebergeur()) {
            case 'vimeo':
                $squelette = $this->vimeoImport($segment);
                if (!(false === $squelette)) {
                    $this->vimeoBuildParameters($squelette);
                    $_importable = true;
                } else {
                    $_importable = false;
                }
                break;

            case 'dailymotion':
                $squelette = $this->dailymotionImport($segment);
                if ($squelette->title == 'Deleted video') {
                    $_importable = false;   
                } else {
                    $this->dailymotionBuildParameters($squelette);
                    $_importable = true;
                }
                break;

            case 'youtube':
                $squelette = $this->youtubeImport($segment);
var_dump($squelette);
                if (!empty($squelette->items)) {
                    print_r($squelette);
                    $video = $squelette->items[0];
                    if (empty($video)) {
                        $_importable = false;
                    } else {
                        $this->youtubeBuildParameters($video);
                        $_importable = true;
                    }                    
                } else {
                    // print_r($squelette); die;
                    $_importable = false;
                }
                // print_r($video); die;
                break;
            
            default:
                $_importable = false;
        }
        return $_importable;
    }

    public function parseHebergeurURL ($url) {
        $domaines = implode('|', $this->_hebergeursWithAPI);

        $patternURL = "/(http:\/\/)?[w\.]*(".$domaines.")\.[a-z]{2,4}\/(.+)$/";
        $valideURL = preg_match($patternURL, $url, $matches);
        if ($valideURL === 1) {
            return $matches;
        } else {
            return false;
        }
    }
}
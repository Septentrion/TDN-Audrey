<?php

namespace TDN\Bundle\DocumentBundle\Twig;

use TDN\Bundle\DocumentBundle\URL\URL;
use TDN\Bundle\DocumentBundle\Entity\DocumentRubrique;


class DocumentExtension extends \Twig_Extension
{
    protected $routeur;

    public function __construct(URL $router) {
        $this->routeur = $router;
    }

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('tags', array($this, 'tagsFilter')),
            new \Twig_SimpleFilter('cascadeTags', array($this, 'cascadeTagsFilter')),
            new \Twig_SimpleFilter('glueSpaces', array($this, 'glueSpacesFilter')),
            new \Twig_SimpleFilter('stripentities', array($this, 'stripEntitiesFilter')),
            new \Twig_SimpleFilter('textCutter', array($this, 'textCutterFilter')),
            new \Twig_SimpleFilter('documentType', array($this, 'documentTypeFilter')),
            new \Twig_SimpleFilter('targetURL', array($this, 'targetURLFilter')),
        );
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('textCutter', array($this, 'textCutterFunction')),
            new \Twig_SimpleFunction('imageTagger', array($this, 'imageTaggerFunction')),
        );
    }

    public function targetURLFilter($document) {
        return $this->routeur->canonicalURL($document);
    }

    public function documentTypeFilter($document) {

        $classe = explode('\\', get_class($document));
        return array_pop($classe);
    }

    public function tagsFilter($tagString)
    {
        $clefs = explode(',', $tagString);
        if (empty($clefs)) {
            return "";
        } elseif (count($clefs) == 1) {
            $clefs = explode(' ', array_pop($clefs));
        }

        $bagSquelette = "<ul class='tagBag'>%s</ul>";
        $tagSquelette = "<li class='tag'><a href='/recherche?seed=\"%s\"'>%s</a></li>";
        $out = "";
        foreach ($clefs as $c) {
            $out .= sprintf($tagSquelette, $c, $c);
        }
                
        return sprintf($bagSquelette, $out);
    }

    public function cascadeTagsFilter($tagString)
    {
        $clefs = explode(',', $tagString);
        if (empty($clefs)) {
            return "";
        } elseif (count($clefs) == 1) {
            $clefs = explode(' ', array_pop($clefs));
        }

        $tagSquelette = "<li class='tag'><a href='/recherche/%s'>%s</a></li>";
        $out = "";
        foreach ($clefs as $c) {
            $out .= sprintf($tagSquelette, $c, $c);
        }
                
        return $tagSquelette;
    }

    public function glueSpacesFilter($chaine)
    {
        $pattern = array();
        $pattern[0] = "#\s+([?!»])#";
        $pattern[1] = "#(«)\s+#";
        $out = array();
        $out[0] = "&nbsp;$1";
        $out[1] = "$1&nbsp;";
        return preg_replace($pattern, $out, $chaine);
    }

    public function stripEntitiesFilter ($texte)
    {
        return html_entity_decode($texte);
    }

    public function textCutterFilter ($texte, $taille = 300) 
    {
        if (strlen($texte) > $taille) {
            $transform = wordwrap($texte, $taille, ' [&hellip;]');
            $cut = strpos($transform, ' [&hellip;]');
            return substr($transform, 0, $cut+11);
        } else {
            return $texte;
        }
    }

    public function textCutterFunction ($texte, $taille = 300) 
    {
        if (strlen($texte) > $taille) {
            $transform = wordwrap($texte, $taille, ' [&hellip;]');
            $cut = strpos($transform, ' [&hellip;]');
            return substr($transform, 0, $cut+11);
        } else {
            return $texte;
        }
    }

    public function imageTaggerFunction ($texte, $options = NULL) 
    {
        $pattternTDN = "/\[image\]http:\/\/(www\.)?trucdenana\.com\/photos\([^\[]+)\[\/image\]/";
        return preg_replace($pattternTDN, "<img src='/uploads/documents/$2' />", $texte);
    }


    public function getName()
    {
        return 'document_extension';
    }
}
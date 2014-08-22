<?php

namespace TDN\Bundle\DocumentBundle\Twig;

use TDN\Bundle\DocumentBundle\Entity\DocumentRubrique;

class RubriqueExtension extends \Twig_Extension
{

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('slugPrincipal', array($this, 'slugPrincipalFilter')),
            new \Twig_SimpleFilter('titrePrincipal', array($this, 'titrePrincipalFilter')),
        );
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('rubriquePrincipale', array($this, 'rubriquePrincipaleFunction')),
            new \Twig_SimpleFunction('hasSponsor', array($this, 'hasSponsorFunction')),
        );
    }

    public function slugPrincipalFilter($rubrique) {

        if ($rubrique instanceof DocumentRubrique) {
            $parent = $rubrique->getRubriqueParente();
            if (is_object($parent)) {
                $marqueur = $parent->getSlug();
            } else {
                $marqueur = $rubrique->getSlug();
            }
        } else {
            $marqueur = $rubrique;
        }

        return $marqueur;
    }

    public function titrePrincipalFilter($rubrique) {

        if ($rubrique instanceof DocumentRubrique) {
            $parent = $rubrique->getRubriqueParente();
            if (is_object($parent)) {
                $marqueur = $parent->getTitre();
            } else {
                $marqueur = $rubrique->getTitre();
            }
        } else {
            $marqueur = $rubrique;
        }

        return $marqueur;
    }

    public function rubriquePrincipaleFunction($rubrique)
    {
        if (is_object($rubrique)) {
            $parent = $rubrique->getRubriqueParente();
            if (is_object($parent)) {
                $marqueur = $parent->getSlug();
            } else {
                $marqueur = $rubrique->getSlug();
            }
        } else {
            $marqueur = $rubrique;
        }

        return $marqueur;
    }

    public function hasSponsorFunction($rubrique)
    {
        if (is_object($rubrique)) {
            $link = $rubrique->getSponsorLink();
            if (!is_null($link)) {
                $style = "background-image: url(/assets/images/sponsors/".$rubrique->getsponsorImage().");";
                $style .= "background-repeat: no-repeat; ";
                $style .= "background-position:  50% top; ";
                $style .= "background-attachment: fixed; ";
                $style .= "background-color: #FFF;";
                // $style .= "background-color: ".$rubrique->getCouleur().";";
            } else {
                $style = '';
            }
        } else {
            $style = 'display:block;';
        }

        return $style;
    }

    public function getName()
    {
        return 'rubrique_extension';
    }
}
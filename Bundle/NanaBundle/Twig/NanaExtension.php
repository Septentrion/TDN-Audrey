<?php

namespace TDN\Bundle\NanaBundle\Twig;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

use TDN\Bundle\NanaBundle\Entity\Nana;

class NanaExtension extends \Twig_Extension
{
    private $generator;

    public function __construct(UrlGeneratorInterface $interface)
    {
        $this->generator = $interface;
    }

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('stiletto', array($this, 'stilettoFilter')),
            new \Twig_SimpleFilter('grade', array($this, 'gradeFilter')),
            new \Twig_SimpleFilter('profilAccess', array($this, 'profilAccessFilter'))
        );
    }

    /**
    *
    * grade Filter
    *
    * calcule de "grade" de la personne (le nombre d'escarpins virtuel)
    *
    * @version 0.1
    *
    * @param JSON $pop — La liste des points glanés par la participation
    * @return integer — le grade
    *
    **/

    public function gradeFilter($pop) {
        $total = 52;
        $_activite = json_decode($pop);
        if (isset($_activite->score)) {
            $_score = $_activite->score;
        } elseif (isset($_activite->$total)) {
            $_score = 0;
            for ($i = 1; $i <= $total ; $i++) {
                $_score += $_activite->$i;
            }
        } else {
            $_score = 0;
        }

        return floor(min(5,$_score/200));
    }

    public function stilettoFilter($pop)
    {
        $total = 52;
        $_squelette = '<img src="http://www.trucsdenana.com/assets/images/theme/centre/profil/%d_escarpins.png" class="stiletto" alt="popularité : %d" data-score="%d" />';
        $_activite = json_decode($pop);
        if (isset($_activite->score)) {
            $_score = $_activite->score;
        } elseif (isset($_activite->$total)) {
            $_score = 0;
            for ($i = 1; $i <= $total ; $i++) {
                $_score += $_activite->$i;
            }
        } else {
            $_score = 0;
        }

        $_grade = floor(min(5,$_score/200));
        $marqueur = sprintf($_squelette, $_grade, $_grade, $_score);
        return $marqueur;
    }

    public function profilAccessFilter ($nana)
    {
       $alink = "<a href='%s'>%s</a>";
       if ($nana->isEnabled()) {
        $url = $this->generator->generate('NanaBundle_profil', array('id' => $nana->getIdNana()));
        return sprintf($alink, $url, $nana->getUsername());
       } else {
        return $nana->getUsername();
       }

    }

    public function getName()
    {
        return 'nana_extension';
    }
}
<?php

namespace TDN\Bundle\CoreBundle\Twig;

class DateExtension extends \Twig_Extension
{
    private $mois = array('janv.', 'févr.', 'mars', 'avr.', 'mai', 'juin', 'juil.', 'août', 'sept.', 'oct.', 'nov.', 'déc.');
 
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('laps', array($this, 'lapsFilter')),
            new \Twig_SimpleFilter('age', array($this, 'ageFilter')),
        );
    }

    public function lapsFilter($date)
    {
        $age = $date->diff(new \DateTime);
        $_minutes = $age->format('%m');
        $_heures = $age->format('%h');
        $_jours = $age->format('%a');
        if ((integer)$_jours >= 2) {
            $m = $this->mois[(integer)$date->format("m") - 1]; 
            $marqueur = "le ".$date->format("d ").$m.$date->format(" Y");
        } elseif ((integer)$_jours == 1) {
            $marqueur = "Hier";
        } elseif ((integer)$_heures > 0) {
            $marqueur = "Il y a $_heures heures";
        } else {
            $marqueur = "Il y a $_minutes minutes";
        }

        return $marqueur;
    }

    public function ageFilter($date)
    {
        if ($date instanceof \DateTime) {
            $age = $date->diff(new \DateTime);
            $_annees = $age->format('%Y');
            $marqueur = $_annees.' an'.($_annees > 1 ? 's' : '');            
        } else {
            $marqueur = "---";
        }
 
        return $marqueur;
    }

    public function getName()
    {
        return 'age_extension';
    }
}
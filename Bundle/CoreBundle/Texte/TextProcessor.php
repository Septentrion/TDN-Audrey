<?php

namespace TDN\Bundle\CoreBundle\Texte;

class TextProcessor {   

    public function flattenAtSeparator($texte, $longueur)
    {
        $nakedTexte = strip_tags($texte);
        if (strlen($nakedTexte) > $longueur) {
            $sample = substr($nakedTexte,0,$longueur);
            $_x = strrpos($sample, ' ');
            $_y = strrpos($sample, '.');
            $_z = strrpos($sample, ',');
            $sample = substr($sample,0,max($_x,$_y,$_z));
            return $sample;
        } else {
            return $nakedTexte;
        }
    }

    public function espaceDoublePonctuation ($texte) {

        $patternDoublePonctuation = "/\s(\?|!\;)/";
        $replaceDoublePonctuation = "&nbsp;$1";
        preg_replace($patternDoublePonctuation, $replaceDoublePonctuation, $texte);

        return $texte;
    }

    public function unicodeOrd($c) {
        if (ord($c{0}) >=0 && ord($c{0}) <= 127)
            return ord($c{0});
        if (ord($c{0}) >= 192 && ord($c{0}) <= 223)
            return (ord($c{0})-192)*64 + (ord($c{1})-128);
        if (ord($c{0}) >= 224 && ord($c{0}) <= 239)
            return (ord($c{0})-224)*4096 + (ord($c{1})-128)*64 + (ord($c{2})-128);
        if (ord($c{0}) >= 240 && ord($c{0}) <= 247)
            return (ord($c{0})-240)*262144 + (ord($c{1})-128)*4096 + (ord($c{2})-128)*64 + (ord($c{3})-128);
        if (ord($c{0}) >= 248 && ord($c{0}) <= 251)
            return (ord($c{0})-248)*16777216 + (ord($c{1})-128)*262144 + (ord($c{2})-128)*4096 + (ord($c{3})-128)*64 + (ord($c{4})-128);
        if (ord($c{0}) >= 252 && ord($c{0}) <= 253)
            return (ord($c{0})-252)*1073741824 + (ord($c{1})-128)*16777216 + (ord($c{2})-128)*262144 + (ord($c{3})-128)*4096 + (ord($c{4})-128)*64 + (ord($c{5})-128);
        if (ord($c{0}) >= 254 && ord($c{0}) <= 255)    //  error
            return FALSE;
        return 0;
    }

    function _unichr($o) {
        if (function_exists('mb_convert_encoding')) {
            return mb_convert_encoding('&#'.intval($o).';', 'UTF-8', 'HTML-ENTITIES');
        } else {
            return chr(intval($o));
        }
    }

    /**
     * Makes slug
     *
     * @param string $slug Chaîne de caractères à transformer
     *
     * @return string 
     */
    public function makeSlug ($str) {

        if($str !== mb_convert_encoding( mb_convert_encoding($str, 'UTF-32', 'UTF-8'), 'UTF-8', 'UTF-32') )
            $str = mb_convert_encoding($str, 'UTF-8', mb_detect_encoding($str));
        $str = htmlentities($str, ENT_NOQUOTES, 'UTF-8');
        $str = preg_replace('`&([a-z]{1,2})(acute|uml|circ|grave|ring|cedil|slash|tilde|caron|lig);`i', '\\1', $str);
        $str = html_entity_decode($str, ENT_NOQUOTES, 'UTF-8');
        $str = preg_replace(array('`[^a-z0-9]`i','`[-]+`'), '-', $str);
        $str = strtolower( trim($str, '-') );
        return $str;
    }
}

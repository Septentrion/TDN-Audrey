<?php

namespace TDN\Bundle\ImageBundle\Twig;

use TDN\Bundle\NanaBundle\Entity\Nana;
use TDN\Bundle\ImageBundle\Entity\Image;
use TDN\Bundle\VideoBundle\Entity\Video;

class RemplacementExtension extends \Twig_Extension {

    private $host = 'http://trucsdenana.com';

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('avatar', array($this, 'avatarFilter')),
        );
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('avatar', array($this, 'avatarFunction')),
            new \Twig_SimpleFunction('mailAvatar', array($this, 'mailAvatarFunction')),
            new \Twig_SimpleFunction('imagePerso', array($this, 'imagePersoFunction')),
            new \Twig_SimpleFunction('illustration', array($this, 'illustrationFunction')),
            new \Twig_SimpleFunction('documentImage', array($this, 'documentImageFunction')),
         );
    }

    public function avatarFilter($who, $format = 'sqr_')
    {
        print_r(get_class($who)); die;
        if ($who instanceof Nana) {
            $idNana = $who->getIdNana();
            $fichier = $who->getLnAvatar()->getFichier();
            $dir = $_SERVER['DOCUMENT_ROOT'].'/uploads/documents/profils/'.$idNana;
                print_r($dir); die;
             if (is_file($dir.'/'.$format.$fichier)) {
                return '/uploads/documents/profils/'.$idNana.'/'.$format.$fichier;
            } elseif ($format != '' && (is_file($dir.'/'.$fichier))) {
                return '/uploads/documents/profils/'.$idNana.'/'.$fichier;
            } else {
                $dir = $_SERVER['DOCUMENT_ROOT'].'../../../v3/web/uploads/documents/profils/'.$idNana;
                 if (is_file($dir.'/'.$format.$fichier)) {
                    return '/uploads/documents/profils/'.$idNana.'/'.$format.$fichier;
                } elseif ($format != '' && (is_file($dir.'/'.$fichier))) {
                    return '/uploads/documents/profils/'.$idNana.'/'.$fichier;
                } else {}
            }
        } 
        
        return 'assets/images/theme/centre/avatar_profil/avatar5%2080x95.png';
    }

    public function avatarFunction($who, $format = NULL) {
        
        if (is_object($who)) {
            $type = get_class($who);
            $type = explode('\\', $type);
            $type = array_pop($type);
            if ($type === 'Nana') {
                $idNana = $who->getIdNana();
                $avatar = $who->getLnAvatar();
                if (is_object($avatar)) {
                    $dir = $_SERVER['DOCUMENT_ROOT'].'/uploads/documents/profils/'.$idNana;
                    $fichier = $avatar->getFichier();
                    switch ($format) {
                        case 'SQUARE':
                        case 'sqr_':
                            $prefix = 'sqr_';
                            break;
                        default:
                            $prefix = "";
                    }
                    if (is_file($dir.'/'.$prefix.$fichier)) {
                        return '/uploads/documents/profils/'.$idNana.'/'.$prefix.$fichier;
                    } elseif ($format != '' && (is_file($dir.'/'.$fichier))) {
                        return '/uploads/documents/profils/'.$idNana.'/'.$fichier;
                    } else {
                        $dir = $_SERVER['DOCUMENT_ROOT'].'../../../v3/web/uploads/documents/profils/'.$idNana;
                         if (is_file($dir.'/'.$prefix.$fichier)) {
                            return '/uploads/documents/profils/'.$idNana.'/'.$prefix.$fichier;
                        } elseif ($format != '' && (is_file($dir.'/'.$fichier))) {
                            return '/uploads/documents/profils/'.$idNana.'/'.$fichier;
                        } else {}
                    }
                 }
            }
        } 
        
        return '/assets/images/theme/centre/avatar_profil/avatar5%2080x95.png';
    }

    public function mailAvatarFunction($who, $format = NULL)
    {
        if ($who instanceof Nana) {
            $idNana = $who->getIdNana();
            $avatar = $who->getLnAvatar();
            if (is_object($avatar)) {
                $dir = $_SERVER['DOCUMENT_ROOT'].'/uploads/documents/profils/'.$idNana;
                $fichier = $avatar->getFichier();
                switch ($format) {
                    case 'SQUARE':
                    case 'sqr_':
                        $prefix = 'sqr_';
                        break;
                    default:
                        $prefix = "";
                }
                if (is_file($dir.'/'.$prefix.$fichier)) {
                    return '/uploads/documents/profils/'.$idNana.'/'.$prefix.$fichier;
                } elseif ($format != '' && (is_file($dir.'/'.$fichier))) {
                    return '/uploads/documents/profils/'.$idNana.'/'.$fichier;
                } else {}
             }
        }
        
        return '/uploads/documents/profils/3/sqr_Justine-42128-1.jpg';
    }

    public function imagePersoFunction($image, $idNana, $format = NULL)
    {
        if ($image instanceof Image) {
            $dir = $_SERVER['DOCUMENT_ROOT'].'/uploads/documents/profils/'.$idNana;
            $fichier = $image->getFichier();
            switch ($format) {
                case 'SQUARE':
                case 'sqr_':
                    $prefix = 'sqr_';
                    break;
                default:
                    $prefix = "";
            }
            if (is_file($dir.'/'.$prefix.$fichier)) {
                return '/uploads/documents/profils/'.$idNana.'/'.$prefix.$fichier;
            } elseif ($format != '' && (is_file($dir.'/'.$fichier))) {
                return '/uploads/documents/profils/'.$idNana.'/'.$fichier;
            } else {
                $dir = $_SERVER['DOCUMENT_ROOT'].'../../../v3/web/uploads/documents/profils/'.$idNana;
                 if (is_file($dir.'/'.$prefix.$fichier)) {
                    return '/uploads/documents/profils/'.$idNana.'/'.$prefix.$fichier;
                } elseif ($format != '' && (is_file($dir.'/'.$fichier))) {
                    return '/uploads/documents/profils/'.$idNana.'/'.$fichier;
                } else {}

            }
        } 
        
        return '/assets/images/theme/centre/avatar_profil/avatar5%2080x95.png';
    }

    public function illustrationFunction($doc, $format = NULL, $id = NULL)
    {
        if (is_object($doc)) {
            if ($doc instanceof Video && !is_null($doc->getVignette())) {
                return $doc->getVignette();
            } else {
                $illustration = $doc->getLnIllustration();
                if ($illustration instanceof Image) {
                    $fichier = $illustration->getFichier();
                    $_annee = $illustration->getDatePublication()->format('Y');
                    $_mois = $illustration->getDatePublication()->format('m');
                    $dir = "uploads/documents/public/$_annee/$_mois/";
                    if (!is_null($id)) {
                        $dir .= "n_/$id/";
                    }
                    switch ($format) {
                        case 'SQUARE':
                        case 'sqr_':
                            $prefix = 'sqr_';
                            break;
                        default:
                            $prefix = "";
                    }
                    $staticDir = $_SERVER['DOCUMENT_ROOT'].$dir.$prefix.$fichier; 
                    if (is_file($staticDir)) {
                        return '/'.$dir.'/'.$prefix.$fichier;
                    } elseif ($format != '' && (is_file($staticDir))) {
                        return '/'.$dir.'/'.$fichier;
                    } else {
                        $staticDir = str_replace('audrey/www', 'v3', $staticDir);
                        if (is_file($staticDir)) {
                            return '/'.$dir.'/'.$prefix.$fichier;
                        } elseif ($format != '' && (is_file($staticDir))) {
                            return '/'.$dir.'/'.$fichier;
                        } else {}
                    }
                }                
            }
        } 
        
        return '/assets/images/theme/centre/avatar_profil/avatar5%2080x95.png';
    }

    public function documentImageFunction($image, $format = NULL, $id = NULL)
    {
        if ($image instanceof Image) {
            $fichier = $image->getFichier();
            $_annee = $image->getDatePublication()->format('Y');
            $_mois = $image->getDatePublication()->format('m');
            $dir = "uploads/documents/public/$_annee/$_mois/";
            $owner = $image->getIdOwner();
            if ($owner instanceof Nana) {
                $dir_nana = "n_/".$owner->getIdNana()."/";
            } else {
                $dir_nana = '';
            }
            if (!is_null($id)) {
                $dir .= "n_/$id/";
            }
            switch ($format) {
                case 'SQUARE':
                case 'sqr_':
                    $prefix = 'sqr_';
                    break;
                default:
                    $prefix = "";
            }
            if ($format != '' && (is_file($_SERVER['DOCUMENT_ROOT'].$dir.$dir_nana.$prefix.$fichier))) {
                return '/'.$dir.$dir_nana.$prefix.$fichier;
            } elseif (is_file($_SERVER['DOCUMENT_ROOT'].$dir.$dir_nana.$fichier)) {
                return '/'.$dir.$dir_nana.$fichier;
            } elseif ($format != '' && (is_file($_SERVER['DOCUMENT_ROOT'].$dir.$prefix.$fichier))) {
                return '/'.$dir.$prefix.$fichier;
            } elseif (is_file($_SERVER['DOCUMENT_ROOT'].$dir.$fichier)) {
                return '/'.$dir.$fichier;
            } else {}
        } 
        
        return '/assets/images/theme/centre/avatar_profil/avatar5%2080x95.png';
    }

    public function getName()
    {
        return 'remplacement_extension';
    }
}
<?php

namespace TDN\Bundle\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CoreController extends Controller {

    public function homeAction()
    {
        return $this->render('TDNCoreBundle:Pages:home.html.twig');
    }

    public function sommaireRubriqueAction($slug) {

        $em = $this->get('doctrine.orm.entity_manager');      

        $repRubriques = $em->getRepository('TDN\Bundle\DocumentBundle\Entity\DocumentRubrique');
        $_r = $repRubriques->findOneBySlug($slug);
        $variables['rubriqueEntity'] = $_r;
        $variables['meta_description'] = $_r->getAbstract();
        if (!is_null($_r->getSponsorLink())) {
            $variables['sponsorLink'] = $_r->getSponsorLink();
        }
        $_ssr = $_r->getSousRubriques();
        if (count($_ssr) === 0) {
            $panel = array($slug);
            $_parent = $_r->getRubriqueParente();
            if (!empty($_parent)) {
                $variables['rubriqueEntity'] = $_parent;
                $variables['rubrique'] = (is_null($_r->getSponsorLink())) ? $_parent->getSlug() : 'sponsored';
        // print_r($variables['rubrique']); die;
            }
        } else {
            $variables['rubrique'] = (is_null($_r->getSponsorLink())) ? $slug : 'sponsored';
            foreach ($_ssr as $_r) {
                $panel[] = $_r->getSlug();
            }
        }

        return $this->render('TDNCoreBundle:Pages:rubrique.html.twig', array('panel' => $panel));
    }
}

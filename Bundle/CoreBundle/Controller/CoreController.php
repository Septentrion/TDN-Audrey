<?php

namespace TDN\Bundle\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CoreController extends Controller {

    public function homeAction()
    {
        return $this->render('TDNCoreBundle:Pages:home.html.twig');
    }

    public function sommaireRubriqueAction($slug)
    {
        return $this->render('TDNCoreBundle:Pages:rubrique.html.twig', array('rubrique' => $slug));
    }
}

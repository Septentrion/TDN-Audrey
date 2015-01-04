<?php

namespace TDN\Bundle\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class InfosController extends Controller {

    public function equipeTDNAction() {

        $em = $this->get('doctrine.orm.entity_manager');      

        $rep = $repository = $em->getRepository('TDN\Bundle\NanaBundle\Entity\Nana');

        $vars['equipe'] = $rep->findByRole('ROLE_ADMIN');
        $_arrJournalistes = $rep->findByRole('ROLE_JOURNALISTE');
        $vars['equipe'] = array_merge_recursive($vars['equipe'], $_arrJournalistes);
        $_arrExperts = $rep->findByRole('ROLE_EXPERT');
        $vars['equipe'] = array_merge_recursive($vars['equipe'], $_arrExperts);

        return $this->render('TDNNanaBundle:Pages:equipeTDN.html.twig', $vars);
    }
}

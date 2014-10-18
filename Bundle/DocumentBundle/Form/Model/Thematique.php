<?php

namespace TDN\Bundle\DocumentBundle\Form\Model;

use Symfony\Component\Validator\Constraints as Assert;

use TDN\Bundle\DocumentBundle\Entity\DocumentRubrique;

class Thematique {
    /**
     * @Assert\Type(type="TDN\DocumentBundle\Entity\DocumentRubrique")
     */
    protected $rubrique;

    public function setRubrique(DocumentRubrique $thematique) {

        $this->rubrique = $thematique;
    }

    public function getRubrique()
    {
        return $this->rubrique;
    }



}

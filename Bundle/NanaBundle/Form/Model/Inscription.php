<?php

namespace TDN\Bundle\NanaBundle\Form\Model;

use Symfony\Component\Validator\Constraints as Assert;

use TDN\Bundle\NanaBundle\Entity\Nana;

class Inscription {

    /**
     * @Assert\Type(type="TDN\Bundle\NanaBundle\Entity\Nana")
     */
    protected $user;

    /**
     * @Assert\NotBlank()
     * @Assert\True()
     */
    protected $termsAccepted;


    protected $token;

    public function setUser(Nana $user)
    {
        $this->user = $user;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function getTermsAccepted()
    {
        return $this->termsAccepted;
    }

    public function setTermsAccepted($termsAccepted)
    {
        $this->termsAccepted = (boolean)$termsAccepted;
    }
    
}
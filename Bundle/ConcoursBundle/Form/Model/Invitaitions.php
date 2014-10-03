<?php

namespace TDN\Bundle\ConcoursBundle\Form\Model;

use Symfony\Component\Validator\Constraints as Assert;

class Invitations {

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    protected $emails;

    protected $token;

    public function addEmail($email)
    {
        $this->emails[] = $email;
    }

    public function removeEmail($email)
    {
        $this->emails->removeElement($email);
    }

    public function getEmails()
    {
        return $this->emails;
    }

    public function setToken(Nana $token)
    {
        $this->token = $token;
    }

    public function getToken()
    {
        return $this->token;
    }

}
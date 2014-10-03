<?php

namespace TDN\Bundle\ConcoursBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use TDN\Bundle\DocumentBundle\Form\Type\DocumentType;

class InvitationType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('emails', 'collection',
            array('type' => 'email',
                  'options' => array(
                    'attr' => array('size' => '42')
                    ),
                  'label' => ' ',
                  'required' => false,
                  'allow_add' => true,
            ));
     }

	public function setDefaultOptions(OptionsResolverInterface $resolver) {

	    $resolver->setDefaults(array(
	    	'data_class' => 'TDN\Bundle\ConcoursBundle\Form\Model\Invitations'
    	));
	}

    public function getName()
    {
        return 'marylin_concours_participation';
    }
}
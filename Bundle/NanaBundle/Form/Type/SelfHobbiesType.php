<?php

namespace TDN\Bundle\NanaBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

// use TDN\Bundle\DocumentBundle\Form\Type\DocumentType;

class SelfHobbiesType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {

        $builder->add('idNana', 'hidden',
            array()
        );
        $builder->add('lnHobbies', 'collection',
            array(
                'label' => 'Mes passions dans la vie',
                'type' => new HobbyType(),
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'prototype' => true,
                'prototype_name' => 'hobby__name__',
                'options' => array('label' => '> hobby', 'attr' => array('class' => 'hobby masque champ-tableau'))
        ));
     }

	public function setDefaultOptions(OptionsResolverInterface $resolver) {

	    $resolver->setDefaults(array(
	    	'data_class' => 'TDN\Bundle\NanaBundle\Entity\Nana',
            'cascade' => true
    	));
	}

    public function getName()
    {
        return 'tdn_nana_hobbies';
    }
}
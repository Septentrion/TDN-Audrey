<?php

namespace TDN\Bundle\NanaBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\Extension\Csrf\CsrfProvider\DefaultCsrfProvider as CsrfProvider;

use TDN\Bundle\NanaBundle\Form\Type\HobbyImagesType;

class HobbyType extends AbstractType {
	
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('domaine', 'text', 
        	array(
                'attr' => array('size' => 60)
        	));
        $builder->add('precisions', 'text', 
        	array(
                'attr' => array('size' => 60)
        	));
        $builder->add('galerieHobby', 'collection', 
            array(
                'type' => new HobbyImagesType(),
                'options' => array(
                    'required' => false
                    ),
                'allow_add' => true
            ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'TDN\Bundle\NanaBundle\Entity\NanaHobby',
            'cascade' => true
        ));
    }

    public function getName()
    {
        return 'tdn_hobby';
    }
}
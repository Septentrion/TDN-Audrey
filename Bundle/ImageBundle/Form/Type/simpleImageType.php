<?php

namespace TDN\Bundle\ImageBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class simpleImageType extends AbstractType {

     public function buildForm (FormBuilderInterface $builder, array $options) {
         $builder->add('upload', 'file', array('label' => 'Choisis une image'));
         $builder->add('titre', 'text', 
            array(
                'label' => 'Légende de l’image',
                'attr' => array('size' => 35)
            ));
    }

	public function setDefaultOptions(OptionsResolverInterface $resolver) {

	    $resolver->setDefaults(array(
	    	'data_class' => 'TDN\Bundle\ImageBundle\Entity\Image',
            'cascade' => true
    	));
	}

    public function getName()
    {
        return 'image';
    }
}
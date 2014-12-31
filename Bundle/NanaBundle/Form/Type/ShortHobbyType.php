<?php

namespace TDN\Bundle\NanaBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ShortHobbyType extends AbstractType {
	
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('domaine', 'text', 
            array(
                'label' => 'Genre'
                ));
        $builder->add('precisions', 'text', 
            array(
                'label' => 'Exemples',
            ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {

        $resolver->setDefaults(array(
            'data_class' => 'TDN\Bundle\NanaBundle\Entity\NanaHobby',
        ));
    }

    public function getName()
    {
        return 'tdn_hobby';
    }
}
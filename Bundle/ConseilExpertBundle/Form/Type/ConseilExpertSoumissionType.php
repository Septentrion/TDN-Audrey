<?php

namespace TDN\Bundle\ConseilExpertBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use TDN\Bundle\ImageBundle\Form\Type\simpleImageType;

class ConseilExpertSoumissionType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

       $builder->add('question', 'textarea', 
            array(
                'label' => 'Quelle est ta question ?'
            ));
       $builder->add('lnImage', new simpleImageType(), 
            array(
                'label' => 'Tu peux joindre une image à ta question',
                'required' => false
            ));
    }


	public function setDefaultOptions(OptionsResolverInterface $resolver) {

	    $resolver->setDefaults(array(
	    	'data_class' => 'TDN\Bundle\ConseilExpertBundle\Entity\ConseilExpert',
            'cascade' => true
    	));
	}

    public function getName()
    {
        return 'tdn3_conseilexpert_soumission';
    }
}
<?php

namespace TDN\Bundle\CauseuseBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use TDN\Bundle\ImageBundle\Form\Type\simpleImageType;
use TDN\Bundle\DocumentBundle\Entity\DocumentRubriqueRepository as RubriqueRepository;

class CauseuseReponseType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

       $builder->add('reponse', 'textarea', 
            array(
                'label' => 'Voici ma réponse :'
            ));
       $builder->add('lnIllustration', new simpleImageType(), 
            array(
                'label' => 'Tu peux joindre une image à ta réponse',
                'required' => false
            ));
    }


	public function setDefaultOptions(OptionsResolverInterface $resolver) {

	    $resolver->setDefaults(array(
	    	'data_class' => 'TDN\Bundle\CauseuseBundle\Entity\Reponse',
            'cascade' => true
    	));
	}

    public function getName()
    {
        return 'tdn3_causeuse_reponse';
    }
}
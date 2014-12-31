<?php

namespace TDN\Bundle\NanaBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\Extension\Csrf\CsrfProvider\DefaultCsrfProvider as CsrfProvider;

use TDN\Bundle\ImageBundle\Form\Type\simpleImageType;

class HobbyImagesType extends AbstractType {
	
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
         $builder->add('lnImage', new simpleImageType(), 
            array(
                'required' => false,
                'label' => 'Ajoute une image'
            ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {

        $resolver->setDefaults(array(
            'data_class' => 'TDN\Bundle\NanaBundle\Entity\NanaHobbyImageProxy',
            'cascade' => true
        ));
    }

    public function getName()
    {
        return 'tdn_image_hobby';
    }
}
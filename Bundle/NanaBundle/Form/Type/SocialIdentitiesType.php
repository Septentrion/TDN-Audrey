<?php

namespace TDN\Bundle\NanaBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
// use Symfony\Component\Form\CsrfProvider\CsrfProviderInterface;
// use Symfony\Component\Form\Extension\Csrf\CsrfProvider\DefaultCsrfProvider as CsrfProvider;

class SocialIdentitiesType extends AbstractType {
	
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('plateforme', 'choice', 
            array(
                'empty_value' => 'Choisis un réseau',
                'choices' => array(
                    'fb' => 'Facebook',
                    'tw' => 'Twitter',
                    'in' => 'Instagram',
                    'pt' => 'Pinterest',
                    'g+' => 'Google+')
                ));
        $builder->add('userID', 'text', 
            array(
                'label' => 'Identifiant',
            ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {

        $resolver->setDefaults(array(
            'data_class' => 'TDN\Bundle\NanaBundle\Entity\NanaSocialNetwork'
        ));
    }

    public function getName()
    {
        return 'tdn_social_identities';
    }
}
<?php

namespace TDN\Bundle\NanaBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
// use Symfony\Component\Form\CsrfProvider\CsrfProviderInterface;
// use Symfony\Component\Form\Extension\Csrf\CsrfProvider\DefaultCsrfProvider as CsrfProvider;

class LoginType extends AbstractType {
	
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('username', 'text', 
            array(
                'label' => 'pseudo'
                ));
        $builder->add('password', 'password', 
            array(
                'label' => 'mot de passe',
            ));
        // $builder->add('_token', 'csrf',
        //     array(
        //         'csrf_provider' => new CsrfProvider(md5('secret')),
        //         'intention' => 'registerCheck'
        //     ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {

        $resolver->setDefaults(array(
            'data_class' => 'TDN\Bundle\NanaBundle\Entity\Nana',
            // 'csrf_protection' => true,
            // 'csrf_field_name' => '_token',
            // 'intention' => 'registerCheck'
        ));
    }

    public function getName()
    {
        return 'tdn_login';
    }
}
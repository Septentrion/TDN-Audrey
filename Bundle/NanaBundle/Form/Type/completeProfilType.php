<?php

namespace TDN\Bundle\NanaBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

// use TDN\Bundle\DocumentBundle\Form\Type\DocumentType;

class completeProfilType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add('username', 'text',
            array(
                'label' => 'Pseudo'
        ));
        $builder->add('password', 'repeated',
            array(
                'label' => 'Mot de passe',
                'type' => 'password',
                'invalid_message' => 'Les mots de passe doivent correspondre',
                'options' => array('required' => true),
                'first_options'  => array('label' => 'Choix'),
                'second_options' => array('label' => 'Validation'),
                'required' => false
        ));
        $builder->add('email', 'email',
            array(
                'label' => 'E-mail',
                'attr' => array('size' => 50)
        ));
        $builder->add('prenom', 'text',
            array(
                'label' => 'Prénom',
                'required' => false,
                'attr' => array('size' => 40)
        ));
        $builder->add('nom', 'text',
            array(
                'label' => 'Nom',
                'required' => false,
                'attr' => array('size' => 40)
        ));
        $builder->add('dateNaissance', 'birthday',
            array(
                'label' => 'Né(e) le',
                'required' => false
        ));
        $builder->add('sexe', 'choice',
            array(
                 'label' => 'Sexe',
                 'multiple' => false,
                 'expanded' => true,
                 'choices' => array('2' =>'Fille', '1' => 'Garçon')
        ));
        $builder->add('ville', 'text',
            array(
                'label' => '',
                'required' => false,
                'attr' => array('size' => 50)
        ));
        $builder->add('occupation', 'text',
            array(
                'label' => 'Job/Etudes',
                'required' => false,
                'attr' => array('size' => 50)
        ));
        $builder->add('offresPartenaires', 'checkbox',
            array(
                'label' => 'Je souhaite recevoir les offres des partenaires de Trucs de Nanas',
                'required' => false
        ));
        $builder->add('newsletter', 'checkbox',
            array(
                'label' => 'Je suis inscrite à la newsletter',
                'required' => false
        ));
        $builder->add('biographie', 'textarea',
            array(
                'label' => '',
                'required' => false
        ));
        $builder->add('filPresence', 'collection',
            array(
                'label' => 'Autres profils',
                'type' => new SocialIdentitiesType(),
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'options' => array('label' => '> profil', 'attr' => array('class' => 'identite-sociale champ-tableau'))
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
        return 'nana_complete_profil';
    }
}
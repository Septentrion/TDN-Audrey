<?php

namespace TDN\Bundle\CommentaireBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository as RubriqueRepository;

class simpleCommentaireType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add('texteCommentaire', 'textarea', 
            array(
                'label' => '_'
                ));
        $builder->add('abonne', 'checkbox', 
            array(
                'required' => false,
                'label' => 'Suivre le fil des commentaires',
                'attr' => array('class' => 'inline-label')
            ));
        $builder->add('idThread', 'hidden', array());
    }


	public function setDefaultOptions(OptionsResolverInterface $resolver) {

	    $resolver->setDefaults(array(
	    	'data_class' => 'TDN\Bundle\CommentaireBundle\Entity\Commentaire',
            // 'data_class' => NULL,
    	));
	}

    public function getName()
    {
        return 'tdn3_commentaire_simple';
    }
}
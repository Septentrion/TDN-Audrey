<?php

namespace TDN\Bundle\DocumentBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Doctrine\ORM\EntityRepository as RubriqueRepository;

use TDN\Bundle\DocumentBundle\Form\Model\Thematique;

class ThematiquePrincipaleType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add('rubrique', 'entity', array(
            'label' => "Choisis une rubrique",
            'class' => 'TDN\Bundle\DocumentBundle\Entity\DocumentRubrique',
            'property' => 'titre',
            'expanded' => false,
            'multiple' => false,
            'query_builder' => function(RubriqueRepository $er) {
                return $er->createQueryBuilder('u')
                          ->where('(u.rubriqueParente = 0 OR u.rubriqueParente IS NULL) AND u.statut = 1');
        }));
    }


	public function setDefaultOptions(OptionsResolverInterface $resolver) {

	    $resolver->setDefaults(array(
	    	'data_class' => 'TDN\Bundle\DocumentBundle\Form\Model\Thematique',
     	));
	}

    public function getName()
    {
        return 'tdn3_thematique';
    }
}
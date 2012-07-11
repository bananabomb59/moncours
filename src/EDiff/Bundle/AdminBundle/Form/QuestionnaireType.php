<?php

namespace EDiff\Bundle\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class QuestionnaireType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('titre')
            ->add('statut')
            
            ->add('statut', 'choice', array(
		    'choices'   => array('1' => 'Ouvert', '2' => 'Fermé'),
		    'required'  => true,
			))
            
            ->add('nb_questions_a_repondre', 'text', array('max_length' => 2, 'required' => true, 'label' => 'Nb Questions'))
            ->add('matiere')
            ->add('anneescolaire')
            ->add('classe')
            ->add('questions')
        ;
    }

    public function getName()
    {
        return 'ediff_bundle_adminbundle_questionnairetype';
    }
}

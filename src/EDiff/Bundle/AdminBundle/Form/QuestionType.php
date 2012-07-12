<?php

namespace EDiff\Bundle\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class QuestionType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('type', 'choice', array(
		    'choices'   => array('qcm' => 'Choix multiple'),
		    'required'  => true,
			))
            
            ->add('niveau', 'choice', array(
		    'choices'   => array('1' => 'Facile', '2' => 'Moyen', '3' => 'Difficile'),
		    'required'  => true,
			))
            
			->add('document')
            ->add('libelle')
            ->add('matiere')
        ;
    }

    public function getName()
    {
        return 'ediff_bundle_adminbundle_questiontype';
    }
}

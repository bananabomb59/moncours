<?php

namespace EDiff\Bundle\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class ReponseType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
        	->add('question')    
        
        	->add('libelle')
            
            ->add('bonne_ou_mauvaise', 'choice', array(
		    'choices'   => array('1' => 'Bonne', '2' => 'Mauvaise'),
		    'required'  => true,
			))
        ;
    }

    public function getName()
    {
        return 'ediff_bundle_adminbundle_reponsetype';
    }
}

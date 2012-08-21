<?php

namespace EDiff\Bundle\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class UserType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
        	->add('droits', 'hidden')
            ->add('login')
            ->add('password')
			->add('nom')
            ->add('prenom')            
            ->add('date_naissance', 'date', array(
			    'widget' => 'single_text',
			    'format' => 'dd/MM/yyyy',
			))
        ;
    }

    public function getName()
    {
        return 'ediff_bundle_adminbundle_usertype';
    }
}

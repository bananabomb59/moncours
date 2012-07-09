<?php

namespace EDiff\Bundle\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class CompetenceType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('libelle')
            ->add('matiere')
        ;
    }

    public function getName()
    {
        return 'ediff_bundle_adminbundle_competencetype';
    }
}

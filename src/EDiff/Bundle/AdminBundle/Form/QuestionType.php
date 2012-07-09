<?php

namespace EDiff\Bundle\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class QuestionType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('type')
            ->add('niveau')
            ->add('libelle')
            ->add('pathtodocument')
            ->add('matiere')
        ;
    }

    public function getName()
    {
        return 'ediff_bundle_adminbundle_questiontype';
    }
}

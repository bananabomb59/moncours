<?php

namespace EDiff\Bundle\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class QuestionnaireEleveType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('numero_question')
            ->add('reponse')
            ->add('questionnaire')
            ->add('question')
            ->add('eleve')
        ;
    }

    public function getName()
    {
        return 'ediff_bundle_adminbundle_questionnaireelevetype';
    }
}

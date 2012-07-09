<?php

namespace EDiff\Bundle\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class CompetenceEleveAnneeType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('nb_points')
            ->add('competence')
            ->add('eleve')
            ->add('anneescolaire')
        ;
    }

    public function getName()
    {
        return 'ediff_bundle_adminbundle_competenceeleveanneetype';
    }
}

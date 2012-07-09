<?php

namespace EDiff\Bundle\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class CompetenceClasseAnneeType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('nb_points_a_atteindre')
            ->add('competence')
            ->add('classe')
            ->add('anneescolaire')
        ;
    }

    public function getName()
    {
        return 'ediff_bundle_adminbundle_competenceclasseanneetype';
    }
}

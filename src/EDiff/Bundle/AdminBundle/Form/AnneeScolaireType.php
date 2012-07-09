<?php

namespace EDiff\Bundle\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class AnneeScolaireType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('annee_debut')
            ->add('annee_fin')
        ;
    }

    public function getName()
    {
        return 'ediff_bundle_adminbundle_anneescolairetype';
    }
}

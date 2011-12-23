<?php

namespace IMRIM\Bundle\LmsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class CourseType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('summary')
            ->add('startDate')
            ->add('expirationDate')
            ->add('autoInscription')
            ->add('category')
        ;
    }

    public function getName()
    {
        return 'imrim_bundle_lmsbundle_coursetype';
    }
}

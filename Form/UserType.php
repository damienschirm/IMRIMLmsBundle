<?php

namespace IMRIM\Bundle\LmsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class UserType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('login')
            ->add('firstName')
            ->add('lastName')
            ->add('mail')
            ->add('isSuspended')
        ;
    }

    public function getName()
    {
        return 'imrim_bundle_lmsbundle_usertype';
    }
}

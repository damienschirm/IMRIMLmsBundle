<?php

namespace IMRIM\Bundle\LmsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class MyProfileType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('firstName', 'text', array(
                'label' => "Prénom",
                'required' => true,
            ))
            ->add('lastName', 'text', array(
                'label' => "Nom",
                'required' => true,
            ))
            ->add('mail', 'email', array(
                'label' => "Mail",
                'required' => true,
            ))
        ;
    }

    public function getName()
    {
        return 'imrim_bundle_lmsbundle_usertype';
    }
}

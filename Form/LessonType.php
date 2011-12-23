<?php

namespace IMRIM\Bundle\LmsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class LessonType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('content')
            ->add('type')
            ->add('fileAttached')
            ->add('coursePosition')
            ->add('creationTime')
            ->add('updateTime')
            ->add('course')
        ;
    }

    public function getName()
    {
        return 'imrim_bundle_lmsbundle_lessontype';
    }
}

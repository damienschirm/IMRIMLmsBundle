<?php

namespace IMRIM\Bundle\LmsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use IMRIM\Bundle\LmsBundle\Entity\Lesson;

class LessonHtmlType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('title','text', array(
                'label' => 'Titre de la leçon',
            ))
            ->add('content', 'textarea')   
            ->add('type', 'hidden', array(
                'required' => true,
            ))
        ;
    }

    public function getName()
    {
        return 'imrim_bundle_lmsbundle_lessontype';
    }
}

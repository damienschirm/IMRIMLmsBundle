<?php

namespace IMRIM\Bundle\LmsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use IMRIM\Bundle\LmsBundle\Entity\Lesson;

class LessonType extends AbstractType
{
    /**
     * Builds the form to create a lesson. 
     * @param FormBuilder $builder
     * @param array $options 
     */
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('title','text', array(
                'label' => 'Titre de la leçon',
            ))
            ->add('type', 'choice', array(
                'label' => 'Type de leçon',
                'required' => true,
                'choices' => array_flip(Lesson::getPossibleTypes()),
            ))
            ->add('content', 'hidden')
        ;
        
    }

    /**
     * Get the name used in the routes for actions related to lessons. 
     * @return string 
     */
    public function getName()
    {
        return 'imrim_bundle_lmsbundle_lessontype';
    }
}

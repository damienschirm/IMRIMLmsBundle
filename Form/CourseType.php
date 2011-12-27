<?php

namespace IMRIM\Bundle\LmsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class CourseType extends AbstractType
{
   /**
    * Builds a form for course creation / edition.
    * @param FormBuilder $builder
    * @param array $options 
    */
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('name', 'text', array(
                'label' => 'Titre du cours',
            ))
            ->add('summary', 'textarea', array(
                'label' => 'Description du cours',
            ))
            ->add('startDate', 'date', array(
                'label' => 'Date de début',
                'widget' => 'single_text',
                'format' => 'dd-MM-yyyy',
            ))
            ->add('expirationDate','date', array(
                'label' => 'Date de fin (optionnelle)',
                'widget' => 'single_text',
                'required' => false,
                'format' => 'dd-MM-yyyy',
            ))
            ->add('autoInscription', 'checkbox', array(
                'label' => 'Tous les utilisateurs peuvent s\'inscrire à ce cours.',
                'required' => false,
                'value' => true,
            ))
            ->add('category', 'entity', array(
                'class' => 'IMRIMLmsBundle:Category',
                'label' => 'Catégorie',
                'required' => true,
                'empty_value' => 'Choisissez une catégorie',
                'empty_data' => null,
            ))
        ;
    }

    /**
     * Get the name of the course form.
     * @return string 
     */
    public function getName()
    {
        return 'imrim_bundle_lmsbundle_coursetype';
    }
}

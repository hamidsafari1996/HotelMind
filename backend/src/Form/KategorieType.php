<?php

namespace App\Form;

use App\Entity\Kategorie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Form type for Kategorie entity.
 * Defines the form fields for creating and editing category entries.
 * This is a simple form with only a name field.
 */
class KategorieType extends AbstractType
{
    /**
     * Builds the category form with a name field.
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
        ;
    }

    /**
     * Configures the options for this form type.
     * Sets the data_class to Kategorie entity.
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Kategorie::class,
        ]);
    }
}

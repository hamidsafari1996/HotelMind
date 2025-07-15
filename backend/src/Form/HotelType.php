<?php

namespace App\Form;

use App\Entity\Hotel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Kategorie;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

/**
 * Form type for Hotel entity.
 * Defines the form fields and validation for creating and editing hotel entries.
 */
class HotelType extends AbstractType
{
    /**
     * Builds the hotel form with all required fields.
     * Includes text fields, image upload, rating, stars, and relationship to Kategorie.
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('location')
            ->add('kategorie', EntityType::class, [
                'class' => Kategorie::class,
                'choice_label' => 'name',
            ])
            ->add('image', FileType::class, 
            [
                'mapped' => false, // Not directly mapped to entity property
                'required' => false,
                'attr' => ['accept' => 'image/*'],
            ])
            ->add('rating', NumberType::class, [
                'label' => 'Rating',
                'required' => false,
                'scale' => 1, // One decimal place
                'attr' => [
                    'min' => 0,
                    'max' => 10,
                    'step' => 0.1
                ]
            ])
            ->add('stars', IntegerType::class, [
                'label' => 'Hotel Stars',
                'required' => false,
                'attr' => [
                    'min' => 1,
                    'max' => 5,
                ]
            ])
            ->add('price')
            ->add('days')
            ->add('person')
            ->add('info')
            ->add('description')
            ->add('created_at', null, [
                'widget' => 'single_text',
            ])
        ;
    }

    /**
     * Configures the options for this form type.
     * Sets the data_class to Hotel entity.
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Hotel::class,
        ]);
    }
}

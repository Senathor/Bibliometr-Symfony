<?php

namespace App\Form;

use App\Entity\Publication;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PublicationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class)
            ->add('authors', TextType::class)
            ->add('publication_date', DateType::class, [
                'widget' => 'single_text',
            ])
            ->add('shares', TextType::class)
            ->add('points', NumberType::class)
            ->add('magazine', TextType::class,
                [
                    'required' => false,
                ])
            ->add('conference', TextType::class,
                [
                    'required' => false,
                ])
            ->add('url', TextType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Publication::class,
        ]);
    }
}

<?php

namespace App\Form;

use App\Entity\Publication;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class,
                [
                    'label' => 'Tytuł',
                    'required' => false,
                ])
            ->add('authors', TextType::class,
                [
                    'label' => 'Autor',
                    'required' => false,
                    "mapped" => false
                ])
            ->add('shares', TextType::class,
                [
                    'label' => 'Udziały',
                    'required' => false,
                    "mapped" => false
                ])
            ->add('points', NumberType::class,
                [
                    'label' => 'Punkty ministerialne',
                    'required' => false,
                ])
            ->add('magazine', TextType::class,
                [
                    'label' => 'Magazyn',
                    'required' => false,
                ])
            ->add('conference', TextType::class,
                [
                    'label' => 'Konferencja',
                    'required' => false,
                ])
            ->add('url', TextType::class,
                [
                    'label' => 'URL/DOI',
                    'required' => false,
                ])
            ->add('data_od', DateType::class, [
                'label' => 'Data do',
                "mapped" => false,
                'required' => false,
                'widget' => 'single_text',
            ])
            ->add('data_do', DateType::class, [
                'label' => 'Data od',
                "mapped" => false,
                'required' => false,
                'widget' => 'single_text',
            ])
            ->add('sort', ChoiceType::class, [
                'label' => 'Sortuj',
                'choices' => [
                    'Rosnąco' => 'ASC',
                    'Malejąco' => 'DESC',
                ],
                'data' => 'ASC',
                "mapped" => false,
                'required' => false,
            ])
            ->add('order', ChoiceType::class, [
                'label' => 'Sortuj według',
                'choices' => [
                    'Nazwa publikacji' => 'title',
                    'Autor publikacji' => 'authors',
                    'Współautor publikacji' => 'shares',
                    'Data publikacji' => 'publication_date',
                    'Punkty' => 'points',
                    'Czasopismo' => 'magazine',
                    'Konferencja' => 'conference',
                ],
                'data' => 'title',
                "mapped" => false,
                'required' => false,
            ])
            ->add('nazwabox', CheckboxType::class, [
                'label' => 'Tytuł',
                'required' => false,
                "mapped" => false,
                'attr' => [
                    'checked' => 'checked',
                ],
            ])
            ->add('authorbox', CheckboxType::class, [
                'label' => 'Autorzy',
                'required' => false,
                "mapped" => false,
                'attr' => [
                    'checked' => 'checked',
                ],
            ])
            ->add('sharesbox', CheckboxType::class, [
                'label' => 'Udziały',
                'required' => false,
                "mapped" => false,
                'attr' => [
                    'checked' => 'checked',
                ],
            ])
            ->add('databox', CheckboxType::class, [
                'label' => 'Data publikacji',
                'required' => false,
                "mapped" => false,
                'attr' => [
                    'checked' => 'checked',
                ],
            ])
            ->add('punktybox', CheckboxType::class, [
                'label' => 'Punkty',
                'required' => false,
                "mapped" => false,
                'attr' => [
                    'checked' => 'checked',
                ],
            ])
            ->add('magazinebox', CheckboxType::class, [
                'label' => 'Magazyn',
                'required' => false,
                "mapped" => false,
            ])
            ->add('conferencebox', CheckboxType::class, [
                'label' => 'Konferencja',
                'required' => false,
                "mapped" => false,
            ])
            ->add('urlbox', CheckboxType::class, [
                'label' => 'URL/DOI',
                'required' => false,
                "mapped" => false,
                'attr' => [
                    'checked' => 'checked',
                ],
            ])
            ->add('search', SubmitType::class, ['label' => 'Szukaj'])
            ->add('export', SubmitType::class, ['label' => 'Eksportuj']);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Publication::class,
        ]);
    }
}

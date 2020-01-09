<?php

namespace App\Form;

use App\Entity\Publication;
use App\Form\AuthorsType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PublicationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                "label" => "Tytuł"
            ]);
            if($options['author_shares'] !== null) {
                $builder->add('author_shares', NumberType::class, [
                    "mapped" => false,
                    "label" => "Moje udziały",
                    "required" => true,
                    'data' => $options['author_shares']
                ]);
            }else{
                $builder->add('author_shares', NumberType::class, [
                    "mapped" => false,
                    "label" => "Moje udziały",
                    "required" => true
                ]);
            }
            if($options['wsauthor_one'] !== null) {
                $builder->add('wsauthor_one', AuthorsType::class, [
                    "mapped" => false,
                    "label" => "Współautor",
                    "required" => false,
                    'data' => $options['wsauthor_one']
                ]);
            }else{
                $builder->add('wsauthor_one', AuthorsType::class, [
                    "mapped" => false,
                    "label" => "Współautor",
                    "required" => false
                ]);
            }
            if($options['wsauthor_two'] !== null) {
                $builder->add('wsauthor_two', AuthorsType::class, [
                    "mapped" => false,
                    "label" => "Współautor",
                    "required" => false,
                    'data' => $options['wsauthor_two']
                ]);
            }else{
                $builder->add('wsauthor_two', AuthorsType::class, [
                    "mapped" => false,
                    "label" => "Współautor",
                    "required" => false
                ]);
            }
            if($options['wsauthor_three'] !== null) {
                $builder->add('wsauthor_three', AuthorsType::class, [
                    "mapped" => false,
                    "label" => "Współautor",
                    "required" => false,
                    'data' => $options['wsauthor_three']
                ]);
            }else{
                $builder->add('wsauthor_three', AuthorsType::class, [
                    "mapped" => false,
                    "label" => "Współautor",
                    "required" => false
                ]);
            }

            $builder->add('publication_date', DateType::class, [
                'widget' => 'single_text',
            ])
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
            'author_shares' => null,
            'wsauthor_one' => null,
            'wsauthor_two' => null,
            'wsauthor_three' => null,
        ]);
    }
}

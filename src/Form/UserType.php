<?php
namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class)
            ->add('name', TextType::class)
            ->add('afiliacja', TextType::class)
        ;

        if ($options["edit_profile"]) {
            $builder
                ->add('password', PasswordType::class, array(
                    // 'empty_data' => '',
                    // 'always_empty' => false,
                    'required' => false,
                    'empty_data' => ''
                    // 'empty_data' => $options["password"]
                ));
        } else {
            $builder
                ->add('password', RepeatedType::class, array(
                    'type' => PasswordType::class,
                    'first_options' => array('label' => 'Hasło'),
                    'second_options' => array('label' => 'Powtórz hasło'),
                ));
        }

        if ($options["show_role"]) {
            $builder->add('role', ChoiceType::class, [
                'choices' => [
                    'Admin' => 'admin',
                    'User' => 'user',
                ],
                'data' => $options['role'],
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => User::class,
            'show_role' => true,
            'role' => "user",
            'edit_profile' => false,
            'password' => ''
        ));
    }
}

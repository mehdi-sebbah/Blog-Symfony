<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('pseudo', TextType::class, [
                "label" => "Pseudo :"
            ])
            ->add('email', TextType::class, [
                "label" => "Email :"
            ])
            ->add('password', PasswordType::class, [
                "label" => "Mot de passe :"
            ])
            ->add('confirm_password', PasswordType::class, [
                "label" => "Confirmer le mot de passe :"
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}

<?php

namespace App\Form;

use App\Entity\Post;
use App\Entity\Comment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class CommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('author', TextType::class, [
                "label" => "Pseudo :"
            ])
            ->add('content', TextareaType::class, [
                "label" => "votre message"
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        //L'option data_class permet de dire a ma class CommentType quel est rattaché par la class Comment
        $resolver->setDefaults([
            'data_class' => Comment::class,
        ]);
    }
}

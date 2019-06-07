<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProfileFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id',HiddenType::class)
            ->add('name',TextType::class)
            ->add('pib',TextType::class,[
                'required' => false
            ])
            ->add('phoneNumber',TextType::class,[
                'required' => false
            ])
            ->add('address',TextType::class,[
                'required' => false
            ])
            ->add('maticniBroj',TextType::class,[
                'required' => false
            ])
            ->add('email',EmailType::class,[
                'required' => false
            ])
            ->add('description',TextareaType::class,[
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}

<?php

namespace App\Form;

use App\Entity\Transaction;
use App\Entity\TransactionType;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TransactionFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('amount',NumberType::class,[
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('transactionType',EntityType::class,[
                'class' => TransactionType::class,
                'placeholder' => 'Select type of transaction',
                'attr' => [
                    'class' => 'browser-default custom-select',
                ],
            ])
            ->add ('submit',SubmitType::class,[
                'attr' => [
                    'class' => 'btn btn-primary btn-block',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Transaction::class,
        ]);
    }
}

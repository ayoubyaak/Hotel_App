<?php

namespace App\Form;

use App\Entity\Reservation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FrontReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('startDate', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date d\'arrivée',
            ])
            ->add('endDate', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date de départ',
            ])
            ->add('guestFullName', TextType::class, [
                'mapped' => false,
                'label' => 'Nom complet',
            ])
            ->add('guestEmail', EmailType::class, [
                'mapped' => false,
                'label' => 'Email',
            ])
            ->add('guestPhone', TextType::class, [
                'mapped' => false,
                'label' => 'Téléphone',
            ])
            ->add('guestCin', TextType::class, [
                'mapped' => false,
                'required' => false,
                'label' => 'CIN',
            ])

            ->add('paymentMethod', ChoiceType::class, [
                'mapped' => false,
                'label' => 'Payment Method',
                'choices' => [
                    'Cash on arrival' => 'cash',
                    'Credit card' => 'card',
                ],
                'expanded' => true, // radio buttons
                'multiple' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
        ]);
    }
}

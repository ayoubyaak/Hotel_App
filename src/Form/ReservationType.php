<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('client', EntityType::class, [
                'class' => Client::class,
                'choice_label' => 'fullName',
                'label' => 'Client'
            ])
            ->add('room', EntityType::class, [
                'class' => Room::class,
                'choice_label' => 'number',
                'label' => 'Chambre'
            ])
            ->add('startDate', DateTimeType::class, [
                'widget' => 'single_text', 'label' => 'Début'
            ])
            ->add('endDate', DateTimeType::class, [
                'widget' => 'single_text', 'label' => 'Fin'
            ]);
        // totalPrice calculé dans le controller
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => Reservation::class]);
    }
}

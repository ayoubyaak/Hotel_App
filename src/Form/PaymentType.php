<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PaymentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('amount', MoneyType::class, ['currency' => 'USD', 'label' => 'Montant'])
            ->add('method', TextType::class, ['label' => 'Méthode'])
            ->add('date', DateTimeType::class, ['widget' => 'single_text', 'label' => 'Date'])
            ->add('reservation', EntityType::class, [
                'class' => Reservation::class,
                'choice_label' => function($r){ return $r->getId().' - '.$r->getClient()?->getFullName(); },
                'label' => 'Réservation'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => Payment::class]);
    }
}

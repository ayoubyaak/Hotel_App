<?php
namespace App\Form;

use App\Entity\Reservation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

class FrontReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // guest fields (if not logged in)
        $builder
            ->add('startDate', DateType::class, ['widget' => 'single_text', 'label'=>'Date d\'arrivée'])
            ->add('endDate', DateType::class, ['widget' => 'single_text', 'label'=>'Date de départ'])
            ->add('guestFullName', TextType::class, [
                'mapped' => false, 'required' => true, 'label' => 'Nom complet'
            ])
            ->add('guestEmail', EmailType::class, [
                'mapped' => false, 'required' => true, 'label' => 'Email'
            ])
            ->add('guestPhone', TextType::class, [
                'mapped' => false, 'required' => true, 'label' => 'Téléphone'
            ])
            ->add('guestCin', TextType::class, ['mapped'=> false, 'required' => false, 'label'=>'CIN']);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class
        ]);
    }
}

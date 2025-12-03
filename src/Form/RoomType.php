<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RoomType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('number', TextType::class, ['label' => 'N° chambre'])
            ->add('price', MoneyType::class, ['currency' => 'USD', 'label' => 'Prix par nuit'])
            ->add('description', TextareaType::class, ['required' => false])
            ->add('available', CheckboxType::class, ['required' => false, 'label' => 'Disponible ?'])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'label' => 'Categorie'
            ])
            ->add('roomClass', EntityType::class, [
                'class' => RoomClass::class,
                'choice_label' => 'name',
                'label' => 'Classe'
            ])
            ->add('imageFile', FileType::class, [
                'label' => 'Image (optionnel)',
                'mapped' => false,
                'required' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => Room::class]);
    }
}


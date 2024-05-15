<?php

namespace App\Form;

use App\Entity\Equipe;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EquipeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('date')
            ->add('image', FileType::class, [
                'label' => 'Image (JPEG, PNG, GIF)',
                'mapped' => false, // Don't map this field to any property of the entity
                'required' => false, // Allow the field to be empty
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Equipe::class,
        ]);
    }
}

<?php

namespace App\Form;

use App\Entity\Produit;
use App\Entity\Categorie;
use App\Repository\CategorieRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('description')
            ->add('prix')
            ->add('quantiteenstock')
            ->add('categorie', EntityType::class, [
                'class' => Categorie::class,
                'choice_label' => 'nomcategorie',
                'query_builder' => function (CategorieRepository $repo) {
                    return $repo->createQueryBuilder('c')
                        ->orderBy('c.nomcategorie', 'ASC');
                },
            ])
            ->add('image', FileType::class, [
                'label' => 'Image (JPEG, PNG, GIF)',
                'mapped' => false, // Don't map this field to any property of the entity
                'required' => false, // Allow the field to be empty
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
        ]);
    }
}
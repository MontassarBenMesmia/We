<?php
namespace App\Form;

use App\Entity\Joueur;
use App\Entity\Equipe;
use App\Repository\EquipeRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class JoueurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('prenom')
            ->add('age')
            ->add('nationalite')
            ->add('email')
            ->add('Equipe', EntityType::class, [
                'class' => Equipe::class,
                'choice_label' => 'name',
                'query_builder' => function (EquipeRepository $repo) {
                    return $repo->createQueryBuilder('c');
                },
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Joueur::class,
        ]);
    }
}

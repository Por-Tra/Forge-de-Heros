<?php

namespace App\Form;

use App\Entity\Character;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints as Assert;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\CharacterClass;
use App\Entity\Race;


class CharacterType extends AbstractType
{
    /**
     * Tableau des coûts pour le système point-buy (budget total de 27 points)
     * Valeur -> Coût
     * 8 -> 0, 9 -> 1, 10 -> 2, 11 -> 3, 12 -> 4, 13 -> 5, 14 -> 7, 15 -> 9
     */
    private const POINT_BUY_COSTS = [
        8 => 0, 9 => 1, 10 => 2, 11 => 3, 12 => 4, 13 => 5, 14 => 7, 15 => 9,
    ];

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('image', FileType::class, [
                'label'    => 'Avatar',
                'mapped'   => false,
                'required' => false,
                'constraints' => [
                    new Assert\File(maxSize: '1024k'),
                ],
            ])
            ->add('name')
            ->add('level', IntegerType::class, [
                'attr' => ['min' => 1, 'max' => 20],
            ])
            ->add('race', EntityType::class, [
                'class'        => Race::class,
                'choice_label' => 'name',
                'placeholder'  => 'Choisir une race',
            ])
            ->add('characterClass', EntityType::class, [
                'class'        => CharacterClass::class,
                'choice_label' => 'name',
                'placeholder'  => 'Choisir une classe',
            ])
            ->add('strength', IntegerType::class, [
                'attr' => ['min' => 8, 'max' => 15],
                'data' => 8
            ])
            ->add('dexterity', IntegerType::class, [
                'attr' => ['min' => 8, 'max' => 15],
                'data' => 8
            ])
            ->add('constitution', IntegerType::class, [
                'attr' => ['min' => 8, 'max' => 15],
                'data' => 8
            ])
            ->add('intelligence', IntegerType::class, [
                'attr' => ['min' => 8, 'max' => 15],
                'data' => 8
            ])
            ->add('wisdom', IntegerType::class, [
                'attr' => ['min' => 8, 'max' => 15],
                'data' => 8
            ])
            ->add('charisma', IntegerType::class, [
                'attr' => ['min' => 8, 'max' => 15],
                'data' => 8
            ]);

            //! supprimer ça quand on aura mis en place le calcul automatique des points de vie dans le controller
            // ->add('healthPoints', IntegerType::class, [
            //     'attr' => ['min' => 1],
            //     'data' => 10
            // ]);
        // healthPoints est retiré : il est calculé automatiquement dans le controller
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Character::class,
        ]);
    }
}

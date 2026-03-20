<?php

namespace App\Form;

use App\Entity\Character;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntegerType; //Integer requirement for an attribute to be >= 0


class CharacterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('level', IntegerType::class, [ // Securing front for users to avoid val < 0
                'attr' => ['min' => 0]
            ])
            ->add('strength', IntegerType::class, [
                'attr' => ['min' => 0]
            ])
            ->add('dexterity', IntegerType::class, [
                'attr' => ['min' => 0]
            ])
            ->add('constitution', IntegerType::class, [
                'attr' => ['min' => 0]
            ])
            ->add('intelligence', IntegerType::class, [
                'attr' => ['min' => 0]
            ])
            ->add('wisdom', IntegerType::class, [
                'attr' => ['min' => 0]
            ])
            ->add('charisma', IntegerType::class, [
                'attr' => ['min' => 0]
            ])
            ->add('healthPoints', IntegerType::class, [
                'attr' => ['min' => 0]
            ])
            ->add('image')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Character::class,
        ]);
    }
}

<?php

namespace App\Form;

use App\Entity\Character;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntegerType; //Integer requirement for an attribute to be >= 0

use Symfony\Component\Form\Extension\Core\Type\FileType; // To be able to upload images through the form
use Symfony\Component\Validator\Constraints as Assert;


class CharacterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('image', FileType::class, [ // Symfony doc code to upload images through the form
                'label' => 'Avatar',

                // unmapped means that this field is not associated to any entity property
                'mapped' => false,

                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,

                // unmapped fields can't define their validation using attributes
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => [
                    new Assert\File(
                        maxSize: '1024k',
                    )
                ],
            ])
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
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Character::class,
        ]);
    }
}

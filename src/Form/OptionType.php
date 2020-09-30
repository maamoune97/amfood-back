<?php

namespace App\Form;

use App\Entity\Option;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OptionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => "Nom de l'option",
                'attr' => [
                    'placeholder' => 'Ex: Taille'
                ],
            ])
            ->add('type', ChoiceType::class, [
                'label' => "Type de l'option",
                'choices'  => [
                    'Obligatoire' => 'radio',
                    'Facultative' => 'checkbox',
                ],
            ])
            ->add('optionFields', CollectionType::class, [
                'entry_type' => OptionFieldType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'label' => 'Champs d\'option',
                'attr' => [
                    
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Option::class,
        ]);
    }
}

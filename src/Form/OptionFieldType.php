<?php

namespace App\Form;

use App\Entity\OptionField;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OptionFieldType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom du champ',
                'attr' => [
                    'placeholder' => 'Ex: XL'
                ]
            ])
            ->add('additionalPrice', NumberType::class, [
                'label' => 'Prix (Fc)',
                'attr' => [
                    'placeholder' => 'Ex: 3500'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => OptionField::class,
        ]);
    }
}

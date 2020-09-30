<?php

namespace App\Form;

use App\Entity\Section;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class SectionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',TextType::class, [
                'label' => 'Nom de la séction',
                'attr' => [
                    'placeholder' => 'Ex: Pizza, Burger, Sandwich, ...'
                ]
            ])
            ->add('status',CheckboxType::class, [
                'label' => 'En ligne',
                'help' => 'faire apparaitre la section sur le menu dans l\'application',
                'required' => false,
            ])
            ->add('image', FileType::class, [
                'label' => 'Image de la séction',
                'help' => "l'image doit être au format jpg ou png et la taille maximal est 3 Mo ",
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    
                    // new NotNull(['message' => "l'image du réstaurant est obligatoire!"]),
                    new File([
                        'maxSize' => '3M',
                        'mimeTypes' => [
                            'image/jpg',
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Selectionnez une image au format jpg ou png',
                    ]),
                    
                ],


            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Section::class,
        ]);
    }
}

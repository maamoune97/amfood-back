<?php

namespace App\Form;

use App\Entity\Article;
use Doctrine\DBAL\Types\FloatType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',TextType::class, [
                'label' => "Nom",
                'attr' => [
                    'placeholder' => 'Ex: bolognaise'
                ]
            ] )
            ->add('ingredient',TextType::class, [
                'label' => "Les ingrédiants",
                'required' => false,
                'help' => 'séparé les un des autres par des virgules',
                'attr' => [
                    'placeholder' => 'Ex: viande haché, mozzarela, sauce tomate, oignons, poivrons'
                ]
            ])
            ->add('price', NumberType::class, [
                'label' => 'Prix (à partir de) en Fc',
                'attr' => [
                    'placeholder' => 'Ex: 3500'
                ]
            ])
            ->add('image', FileType::class, [
                'label' => "Image de l'article",
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
            // ->add('options', CollectionType::class, [
            //     'entry_type' => OptionType::class,
            //     'allow_add' => true,
            //     'allow_delete' => true,
            // ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}

<?php

namespace App\Form;

use App\Entity\City;
use App\Entity\Restaurant;
use App\Entity\User;
use App\Repository\IslandRepository;
use App\Repository\UserRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotNull;

class RestaurantType extends AbstractType
{
    private $islands;
    
    public function __construct(IslandRepository $islandRepository)
    {
        $this->islands = $islandRepository->findAll();
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => "Nom du réstaurant"
            ])
            ->add('phone',TextType::class, [
                'label' => 'Téléphone du réstaurant'
            ])
            ->add('email', EmailType::class, [
                'label' => 'Adresse email du réstaurant (facultatif)',
                'required' => false,
                'attr' => [

                ]
            ])
            ->add('activate', CheckboxType::class, [
                'label' => 'En ligne (visible sur l\'application)',
                'required' => false,
                'attr' => [

                ]
            ])
            ->add('imageLogo', FileType::class,[
                'help' => "l'image doit être au format jpg ou png et la taille maximal est 3 Mo ",
                'label' => 'Image de couverture (logo, marque, ...)',
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
            ->add('location',EntityType::class, [
                'class' => City::class,
                'choice_label' => 'name',
                'group_by' => function($choice) {
                    foreach ($this->islands as $island) {

                        if ($choice->getIsland() == $island) {
                            return $island->getName();
                        }
                
                    }
                },
                'label' => 'Ville'
                
            ])
            ->add('speciality', TextType::class, [
                'label' => "spécialitées du réstaurant"
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Restaurant::class,
        ]);
    }
}

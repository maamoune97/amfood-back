<?php

namespace App\Form;

use App\Entity\City;
use App\Entity\DeliveryMan;
use App\Repository\IslandRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DeliveryManType extends AbstractType
{
    private $islands;
    
    public function __construct(IslandRepository $islandRepository)
    {
        $this->islands = $islandRepository->findAll();
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('city', EntityType::class, [
                'class' => City::class,

                'choice_label' => 'name',

                'group_by' => function($choice) {
                    foreach ($this->islands as $island) {

                        if ($choice->getIsland() == $island) {
                            return $island->getName();
                        }
                
                    }
                },

                'label' => 'ville des livraison'

            ])
            ->add('phone', TextType::class, [
                // 'disabled' => true,
                'attr' => [
                    'class' => 'nav-link disabled d-none'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => DeliveryMan::class,
        ]);
    }
}

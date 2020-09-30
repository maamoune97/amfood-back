<?php

namespace App\Form;

use App\Entity\City;
use App\Entity\DeliveryMan;
use App\Entity\User;
use App\Repository\IslandRepository;
use App\Repository\UserRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
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
            ->add('user', EntityType::class, [
                'class' => User::class,

                // 'query_builder' => function (UserRepository $ur) {
                //     return $ur->createQueryBuilder('u')
                //         ->where('u.roles IS NOT (:role)')
                //         ->setParameter('role', 'ROLE_DELIVERY_MAN')
                //         ->orderBy('u.fullName', 'ASC');
                // },

                'choice_label' => function ($user) {
                    return $user->getFullName().' ( '.$user->getPhone().' )';
                },

                'label' => 'Utilisateur',
            ])
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

                'label' => 'ville assurée'

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

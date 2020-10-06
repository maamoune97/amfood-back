<?php

namespace App\Controller;

use App\Entity\Restaurant;
use App\Entity\RestaurantManager;
use App\Repository\RestaurantManagerRepository;
use App\Repository\UserRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/restaurant/manager", name="admin_restaurant_manager_")
 */
class AdminRestaurantManagerController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(RestaurantManagerRepository $restaurantManagerRepository)
    {
        return $this->render('admin_restaurant_manager/index.html.twig', [
            'managers' => $restaurantManagerRepository->findAllSimplified(),
        ]);
    }

    /**
     * @Route("/show{id}", name="show")
     */
    public function show()
    {
        return $this->render('admin_restaurant_manager/show.html.twig', [
            
        ]);
    }
    
    /**
     * @Route("/new/restaurant/{restaurant}", name="new")
     */
    public function new(Restaurant $restaurant, Request $request, EntityManagerInterface $manager, UserRepository $userRepository)
    {

        $searchUserform = $this->createFormBuilder()
            ->add('phone', SearchType::class, [
                'label' => 'Numéro de téléphone',
                'attr' => [
                    'placeholder' => 'Entrez le numéro de téléphone'
                ]])
            ->getForm();

        $searchUserform->handleRequest($request);

        if ($searchUserform->isSubmitted() && $searchUserform->isValid()) {

            $data = $searchUserform->getData();

            $user = $userRepository->findOneByPhone($data['phone']);
            $tryToFind = true;
        }

        $restaurantManagerform = $this->createFormBuilder()
            ->add('userphone', TextType::class, [
                'attr' => [
                    'class' => 'nav-link disabled d-none'
                ]])
            ->getForm();

        $restaurantManagerform->handleRequest($request);

        if ($restaurantManagerform->isSubmitted() && $restaurantManagerform->isValid()) 
        {
            $data = $restaurantManagerform->getData();
            $user = $userRepository->findOneByPhone($data['userphone']);
            
            $restaurantManager = new RestaurantManager();
            $restaurantManager->setCreatedAt(new DateTime())
                              ->setRestaurant($restaurant)
                              ->setUser($user);
            
            $user->addRole('ROLE_MANAGER');

            $manager->persist($user);
            $manager->persist($restaurantManager);
            $manager->flush();

            return $this->redirectToRoute('admin_restaurant_manager_index');

        }

        return $this->render('admin_restaurant_manager/new.html.twig', [
            'restaurant' => $restaurant,
            'searchUserform' => $searchUserform->createView(),
            'restaurantManagerform' => isset($restaurantManagerform) ? $restaurantManagerform->createView() : null,
            'user' => isset($user) ? $user : null,
            'tryToFind' => isset($tryToFind),
        ]);
    }
}

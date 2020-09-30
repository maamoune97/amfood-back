<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * @Route("/restaurant/manager", name="restaurant_manager_")
 */
class RestaurantManagerAccountController extends AbstractController
{

    /**
     * @Route("/login", name="login")
     */
    public function login(AuthenticationUtils $authenticationUtils)
    {
        return $this->render('restaurant_manager/account/login.html.twig', [
            'error' => $authenticationUtils->getLastAuthenticationError(),
            'lastUser' =>$authenticationUtils->getLastUsername()
        ]);
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logout()
    {
        
    }
    
}

<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AccountController extends AbstractController
{
    /**
     * @Route("admin/login", name="account_login")
     */
    public function login()
    {
        return $this->render('account/login.html.twig', [
            
        ]);
    }
    /**
     * @Route("/logout", name="account_logout")
     */
    public function logout()
    {
        
    }
}

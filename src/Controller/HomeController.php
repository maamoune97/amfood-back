<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


class HomeController extends AbstractController
{
    /**
     * @Route("/admin/home", name="home_index")
     */
    public function index()
    {
        return $this->render('home/home.html.twig', [
            
        ]);
    }

    /**
     * @Route("/", name="index")
     */
    public function redirectToIndex()
    {
        return $this->redirectToRoute('home_index');
    }


}

<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ServerBag;
use Symfony\Component\Routing\Annotation\Route;


class HomeController extends AbstractController
{
    /**
     * @Route("/admin/home", name="home_index")
     */
    public function index(Request $request)
    {
        dump($_SERVER);
        dump($request);
        dump($request->server->get('SERVER_PROTOCOL').'://'.$request->server->get('HTTP_HOST'));
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

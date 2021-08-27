<?php

namespace App\Controller;

use App\Repository\CityRepository;
use App\Repository\DeliveryManRepository;
use App\Repository\OrderRepository;
use App\Repository\RestaurantRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


class HomeController extends AbstractController
{
    /**
     * @Route("/admin/home", name="home_index")
     */
    public function index(UserRepository $ur, OrderRepository $or, DeliveryManRepository $dmr, RestaurantRepository $rr, CityRepository $cr)
    {


        $figures = [];
        $figures['users'] = count($ur->findAll());
        $figures['orders'] = count($or->findAll());
        $figures['delivered'] = count($or->findByStatus(5));
        $figures['deliveryMen'] = count($dmr->findAll());
        $figures['restaurants'] = count($rr->findAll());
        $figures['cities'] = count($cr->findAll());

        $orders = [];
        // TODO remplace $or->findAll() by $or->findByStatus(6) for just have delivered orders
        foreach ($or->findAll() as $order)
        {
            $month = $order->getCreatedAt()->format('m-Y');
            $orders[$month][]=$order;
        }

        return $this->render('home/home.html.twig', [
            'figures' => $figures,
            'orders' => $orders,
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

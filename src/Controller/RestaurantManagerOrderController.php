<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\MercureCookieGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/restaurant/manager/orders", name="restaurant_manager_order_")
 */
class RestaurantManagerOrderController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(MercureCookieGenerator $cookieGenerator)
    {
        $response = $this->render('restaurant_manager/order/index.html.twig', [
            
        ]);

        $response->headers->set('set-cookie', $cookieGenerator->generate($this->getUser()));

        return $response;
    }

    /**
     * @Route("/check/{user}", name="check", methods={"POST"})
     */
    public function checkUsers(MessageBusInterface $bus, ?User $user = null)
    {
        // $target = $user !== null ? ["http://monsite.com/restaurant/{$user->getId()}"] : [];

        $update = new Update("http://monsite.com/restaurant/", "[]", true);

        $bus->dispatch($update);
        return new Response('event published');
    }
}

<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\User;
use App\Service\MercureCookieGenerator;
use App\Service\OrderService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/restaurant/manager/orders", name="restaurant_manager_order_")
 */
class RestaurantManagerOrderController extends AbstractController
{
    private OrderService $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

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
     * @Route("/command-old", name="command_old")
     */
    public function command(MercureCookieGenerator $cookieGenerator)
    {
        return $this->render('restaurant_manager/order/command.html.twig', [
            
        ]);
    }

    /**
     * @Route("/command", name="command")
     */
    public function index2(MercureCookieGenerator $cookieGenerator)
    {
        return $this->render('restaurant_manager/order/index2.html.twig', [
            
        ]);
    }

    /**
     * @Route("/check/{user}", name="check", methods={"POST"})
     */
    public function checkUsers(MessageBusInterface $bus, ?User $user = null)
    {
        // $target = $user !== null ? ["http://monsite.com/restaurant/{$user->getId()}"] : [];

        $update = new Update("http://monsite.com/restaurant", "[]", true);

        $bus->dispatch($update);
        return new Response('event published');
    }

    /**
     * Get current user's waiting command 
     *
     * @Route("/waiting", name="waiting")
     * 
     * @return Response
     */
    public function getWaiting() : Response
    {
        return $this->render("restaurant_manager/order/waiting.html.twig",[
            'orders' => $this->orderService->findByStatus(0),
        ]);
    }

    /**
     * Get current user's in preparation command 
     *
     * @Route("/in-preparation", name="in_preparation")
     * 
     * @return Response
     */
    public function getInPreparation() : Response
    {
        return $this->render("restaurant_manager/order/inPreparation.html.twig",[
            'orders' => $this->orderService->findByStatus(1),
        ]);
    }

    /**
     * Get current user's finished command 
     *
     * @Route("/finished", name="finished")
     * 
     * @return Response
     */
    public function getInFinished() : Response
    {
        return $this->render("restaurant_manager/order/finished.html.twig",[
            'orders' => $this->orderService->findByStatus(3),
        ]);
    }

    /**
     * find command by id
     *
     * @Route("/{id}", name="getOrder")
     * 
     * @return JsonResponse
     */
    public function getOrder($id) : JsonResponse
    {
        return $this->json($this->orderService->customizedFind($id));
    }

    /**
     * Accept a commande
     *
     * @Route("/accept-order/{id}", name="accept_order")
     * 
     * @return JsonResponse
     */
    public function acceptOrder(Order $order, EntityManagerInterface $manager) : Response
    {
        $order->setStatus(1);
        $manager->persist($order);
        $manager->flush();

        return $this->redirectToRoute("restaurant_manager_order_waiting");
    }

}

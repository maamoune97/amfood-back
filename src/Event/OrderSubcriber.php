<?php

namespace App\Event;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Order;
use App\Entity\User;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Security;

class OrderSubcriber implements EventSubscriberInterface
{
    private User $user;
    private EntityManagerInterface $manager;

    public function __construct(Security $security, EntityManagerInterface $manager)
    {
        $this->user = $security->getUser();
        $this->manager = $manager;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['handleOrder', EventPriorities::PRE_VALIDATE],
            KernelEvents::VIEW => ['hideRefuseOrdersToDelivery', EventPriorities::PRE_SERIALIZE],
        ];
    }


    /**
     * Masque les commandes réfusée dans la liste des commandes en attente
     *
     * @param ViewEvent $event
     * @return void
     */
    public function hideRefuseOrdersToDelivery(ViewEvent $event)
    {

        //parametre à rajouter et assigné la valeur 1 dans la requete pour appliquer ce "filtre"
        $hideRefused = $event->getRequest()->get("hide_refused") == '1' ? true : false;
        
        $method = $event->getRequest()->getMethod();
        $normalizationContext = $event->getRequest()->get('_api_normalization_context') ?? false;

        if ($normalizationContext && $method === 'GET')
        if ($normalizationContext['operation_type'] === "collection" && $normalizationContext['resource_class'] === Order::class && $hideRefused)
        {
            $orders = $event->getControllerResult();;

            $oredersIdRefused = [];
            foreach ($this->user->getDeliveryMan()->getOrdersRefused() as $refuse) {
                $oredersIdRefused[] = $refuse->getCommand()->getId();
            }

            $cleanOrders = [];

            foreach ($orders as $order) {
                if (!in_array($order->getId(), $oredersIdRefused)) {
                    $cleanOrders[] = $order;
                }
            }

            $event->setControllerResult($cleanOrders);
        }
    }


    public function handleOrder(ViewEvent $event)
    {
        $result = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if ($result instanceof Order && $method === 'POST')
        {
            $order = $result;
            unset($result);
            // dd($order);

            $order->setCustomer($this->user);
            $order->setCreatedAt(new DateTime());

            foreach ($order->getOrderArticlePacks() as $orderArticlePack) {
                $orderArticlePack->setCommand($order);
                $this->manager->persist($orderArticlePack);
            }

            //Gerer les options des articles
            //dd($order);
        }
    }
}

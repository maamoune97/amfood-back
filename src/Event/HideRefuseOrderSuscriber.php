<?php

namespace App\Event;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Order;
use App\Entity\User;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Security;

class HideRefuseOrderSuscriber implements EventSubscriberInterface
{
    private User $user;

    public function __construct(Security $security)
    {
        if ($security->getUser())
        {
            $this->user = $security->getUser();
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['hideRefuseOrdersToDelivery', EventPriorities::PRE_VALIDATE],
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
        //dd("hideRefuseOrdersToDelivery", $event);
        
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
}

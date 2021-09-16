<?php

namespace App\Event;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Order;
use App\Entity\User;
use DateTime;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Security;

class HandleNewOrderSuscriber implements EventSubscriberInterface
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
            KernelEvents::VIEW => ['handleOrder', EventPriorities::PRE_VALIDATE]
        ];
    }


    public function handleOrder(ViewEvent $event)
    {
        //dd("handleOrder", $event);
        $result = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if ($result instanceof Order && $method === 'POST')
        {
            $order = $result;
            unset($result);

            //check if restaurant is open to be able to continue
            if(!$order->getRestaurant()->isOpen())
            {
                $data = ['error' => true, 'ErrorMessage' => 'Restaurant closed', 'errorCode' => 1];
                $event->setControllerResult(new JsonResponse($data, 400));
                return;
            }
            
            $order->setCustomer($this->user)
                  ->setCreatedAt(new DateTime())
                  ->setOrderNumber((new DateTime)->format('sH-z-yi'));
        }
    }
}

<?php

namespace App\Event;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\Order;
use DateTime;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Security;

class OrderSubcriber implements EventSubscriberInterface
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['handleOrder', EventPriorities::PRE_VALIDATE],
        ];
    }

    public function handleOrder(ViewEvent $event)
    {
        $result = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if ($result instanceof Order && $method === 'POST')
        {
            $order = $result;
            unset($result);

            $order->setCustomer($this->security->getUser());
            $order->setCreatedAt(new DateTime());

            //Gerer les options des articles
            //dd($order);
        }
    }
}

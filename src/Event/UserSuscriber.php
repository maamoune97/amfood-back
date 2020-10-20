<?php

namespace App\Event;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\User;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Security;

class UserSuscriber extends ParameterBag implements EventSubscriberInterface
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }
    public static function getSubscribedEvents() : array
    {
        return [
            KernelEvents::VIEW => ['getCurrentUser', EventPriorities::PRE_SERIALIZE],
        ];
    }

    public function getCurrentUser(ViewEvent $event)
    {
        $method = $event->getRequest()->getMethod();
        $operationType = $event->getRequest()->attributes->parameters['_api_normalization_context']['operation_type'];
        $apiResourceClass = $event->getRequest()->attributes->parameters['_api_resource_class'];
        
        
        if ($operationType === "collection" && $method === 'GET' &&  $apiResourceClass === User::class)
        {
            $user = $this->security->getUser();
            $event->setControllerResult($user);
        }
    }

}

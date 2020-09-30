<?php

namespace App\Event;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\User;
use Doctrine\Common\Collections\Expr\Value;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class PasswordEncoderSubcriber implements EventSubscriberInterface
{
    private $encoder;
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
       $this->encoder = $encoder; 
    }
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['encodePassword', EventPriorities::PRE_WRITE],
        ];
    }

    public function encodePassword(ViewEvent $event)
    {
        $result = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();
        if ($result instanceof User && $method === 'POST')
        {
            $user = $result;
            unset($result);

            $passwordHash = $this->encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($passwordHash);
        }
    }
}

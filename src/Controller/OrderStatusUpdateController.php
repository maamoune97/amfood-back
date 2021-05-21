<?php

namespace App\Controller;

use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Security;

class OrderStatusUpdateController extends AbstractController
{
    private $manager;
    private $security;

    public function __construct(EntityManagerInterface $manager,Security $security)
    {
        $this->manager = $manager;
        $this->security = $security;
    }

    public function __invoke(Order $order, int $status): JsonResponse
    {
        switch ($status)
        {
            case 0:
                $order->setStatus($status);
                $this->manager->persist($order);
                $this->manager->flush();
                return $this->json(['error' => false, 'errorMessage' => null]);
                break;

            case 2:
                if ($order->getStatus() !== "1")
                {
                    return $this->json(['error' => true, 'errorMessage' => "current status is {$order->getStatus()}, it must be equal 1 to update it to 2"], 500);
                }

                $order->setStatus($status);

                $delivery = $order->getDelivery();
                $delivery->setDeliveryMan($this->security->getUser()->getDeliveryMan());
                $order->setDelivery($delivery);

                $this->manager->persist($delivery);
                $this->manager->persist($order);
                $this->manager->flush();

                return $this->json(['error' => false, 'errorMessage' => null]);
                break;
            
            case 3:
                if ($order->getStatus() !== "2")
                {
                    return $this->json(['error' => true, 'errorMessage' => "current status is {$order->getStatus()}, it must be equal 2 to update it to 3"], 500);
                }
                $order->setStatus($status);
                $this->manager->persist($order);
                $this->manager->flush();
                return $this->json(['error' => false, 'errorMessage' => null]);
                break;
            
            case 4:
                if ($order->getStatus() !== "3")
                {
                    return $this->json(['error' => true, 'errorMessage' => "current status is {$order->getStatus()}, it must be equal 3 to update it to 4"], 500);
                }
                $order->setStatus($status);
                $this->manager->persist($order);
                $this->manager->flush();
                return $this->json(['error' => false, 'errorMessage' => null]);
                break;

            case 5:
                if ($order->getStatus() !== "4")
                {
                    return $this->json(['error' => true, 'errorMessage' => "current status is {$order->getStatus()}, it must be equal 4 to update it to 5"], 500);
                }
                $order->setStatus($status);
                $this->manager->persist($order);
                $this->manager->flush();
                return $this->json(['error' => false, 'errorMessage' => null]);
                break;
            
            default:
                return $this->json(['error' => true, 'errorMessage' => "$status is invalid status"], 500);
                break;
        }
    }
    
}

<?php

namespace App\Controller;

use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class OrderStatusUpdateController extends AbstractController
{
    public function __invoke(Order $order, int $status, EntityManagerInterface $manager): JsonResponse
    {
        switch ($status)
        {
            case -1:
                if ($order->getStatus() !== "1")
                {
                    return $this->json(['error' => true, 'errorMessage' => "current status is {$order->getStatus()}, it must be equal 1 to update it to -1"], 400);
                }
                $order->setStatus($status);
                $manager->persist($order);
                $manager->flush();
                return $this->json(['error' => false, 'errorMessage' => null]);
                break;
            
            case 0:
                $order->setStatus($status);
                $manager->persist($order);
                $manager->flush();
                return $this->json(['error' => false, 'errorMessage' => null]);
                break;

            case 2:
                if ($order->getStatus() !== "1")
                {
                    return $this->json(['error' => true, 'errorMessage' => "current status is {$order->getStatus()}, it must be equal 1 to update it to 2"], 400);
                }

                $order->setStatus($status);

                $delivery = $order->getDelivery();
                $delivery->setDeliveryMan($this->getUser()->getDeliveryMan());
                $order->setDelivery($delivery);

                $manager->persist($delivery);
                $manager->persist($order);
                $manager->flush();

                return $this->json(['error' => false, 'errorMessage' => null]);
                break;
            
            case 3:
                if ($order->getStatus() !== "2")
                {
                    return $this->json(['error' => true, 'errorMessage' => "current status is {$order->getStatus()}, it must be equal 2 to update it to 3"], 400);
                }
                $order->setStatus($status);
                $manager->persist($order);
                $manager->flush();
                return $this->json(['error' => false, 'errorMessage' => null]);
                break;
            
            case 4:
                if ($order->getStatus() !== "3")
                {
                    return $this->json(['error' => true, 'errorMessage' => "current status is {$order->getStatus()}, it must be equal 3 to update it to 4"], 400);
                }
                $order->setStatus($status);
                $manager->persist($order);
                $manager->flush();
                return $this->json(['error' => false, 'errorMessage' => null]);
                break;

            case 5:
                if ($order->getStatus() !== "4")
                {
                    return $this->json(['error' => true, 'errorMessage' => "current status is {$order->getStatus()}, it must be equal 4 to update it to 5"], 400);
                }
                $order->setStatus($status);
                $manager->persist($order);
                $manager->flush();
                return $this->json(['error' => false, 'errorMessage' => null]);
                break;
            
            case 6:
                if ($order->getStatus() !== "5")
                {
                    return $this->json(['error' => true, 'errorMessage' => "current status is {$order->getStatus()}, it must be equal 5 to update it to 6"], 400);
                }
                $order->setStatus($status);
                $manager->persist($order);
                $manager->flush();
                return $this->json(['error' => false, 'errorMessage' => null]);
                break;
            
            default:
                return $this->json(['error' => true, 'errorMessage' => "$status is invalid status"], 400);
                break;
        }
    }
    
}

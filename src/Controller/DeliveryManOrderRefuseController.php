<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\OrderRefused;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class DeliveryManOrderRefuseController extends AbstractController
{

    /**
     * @param Order $order
     * @param EntityManagerInterface $manager
     * @return JsonResponse
     */
    public function __invoke(Order $order, EntityManagerInterface $manager): JsonResponse
    {
        try
        {
            foreach ($this->getUser()->getDeliveryMan()->getOrdersRefused() as $refuse)
            {
                if ($refuse->getCommand()->getId() === $order->getId())
                {
                    return $this->json(["error" => true, "errorMessage" => "order already refused by this delivery man"]);
                    break;
                }
            }
            
            $refused = new OrderRefused();
            $refused->setCommand($order)
                    ->setDeliveryMan($this->getUser()->getDeliveryMan());
            
            $manager->persist($refused);
            $manager->flush();

            return $this->json(["error" => false, "errorMessage" => null]);
        } 
        catch (\Throwable $th)
        {
            //throw $th;
            return $this->json(["error" => true, "errorMessage" => $th->getMessage()]);
        }

    }
}

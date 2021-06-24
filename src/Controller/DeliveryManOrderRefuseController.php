<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\OrderRefused;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DeliveryManOrderRefuseController extends AbstractController
{
    /**
     * @param Order $order
     * @param EntityManagerInterface $manager
     * @return OrderRefused $refused
     */
    public function __invoke(Order $order, EntityManagerInterface $manager): OrderRefused
    { 
        $refused = new OrderRefused();
        $refused->setCommand($order)
                ->setDeliveryMan($this->getUser()->getDeliveryMan());
        
        $manager->persist($refused);
        $manager->flush();

        return $refused;
    }
}

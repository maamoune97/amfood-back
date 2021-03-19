<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\OrderRepository;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Security;

class OrderService
{
    private OrderRepository $orderRepository;
    private User $currentUser;
    private $router;

     /**
    *  __Construct method
    * @param OrderRepository $orderRepository
    * @param Security $security
    */
    public function __construct(OrderRepository $orderRepository, Security $security, UrlGeneratorInterface $router)
    {
        $this->orderRepository = $orderRepository;
        $this->currentUser = $security->getUser();
        $this->router = $router;
    }

    public function findByStatus(int $status) : array
    {
        //$user = $this->security->getUser();

        $restaurant = $this->currentUser->getRestaurantManager()->getRestaurant();
        
        $orders = $this->orderRepository->findBy(['restaurant' => $restaurant, 'status' => $status],['createdAt' => 'ASC']);

        return $orders;

    }
    
    /**
     * Find Specific data for Visualization views 
     *
     * @param integer $id
     * @return array
     */
    public function customizedFind(int $id) : array
    {
        $order = $this->orderRepository->find($id);
        $orderData = [];

        $orderData['id'] = $order->getId();
        $orderData['createdAt'] = $order->getCreatedAt();
        $orderData['status'] = $order->getStatus();
        $orderData['totalPrice'] = $order->getTotalPrice();

        $acceptOrderUrl = $this->router->generate(
            'restaurant_manager_order_accept_order',
            ['id' => $order->getId()]
        );
        $orderData['acceptOrderUrl'] = $acceptOrderUrl;

        $orderArticlePacks = [];
        
        foreach ($order->getOrderArticlePacks() as $keyorderArticlePack => $orderArticlePack) {
            $pack = [];
            $pack['quantity'] = $orderArticlePack->getQuantity();
            $pack['article'] = $orderArticlePack->getArticle()->getName();
            $pack['price'] = $orderArticlePack->getArticle()->getPrice();
            $pack['options'] = [];

            foreach ($orderArticlePack->getOptionFieldsTaken() as $optionField) {
                $options = [];
                $options['optionName'] = $optionField->getMyOption()->getName();
                $options['optionFieldName'] = $optionField->getName();
                $options['addPrice'] = $optionField->getAdditionalPrice();
                $pack['options'][] = $options;
            }

            $orderArticlePacks[$keyorderArticlePack] = $pack;
        }

        $orderData['articlePacks'] = $orderArticlePacks;

        return $orderData;
    }

}

<?php

namespace App\Entity;

use App\Repository\RatingRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RatingRepository::class)
 */
class Rating
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $restaurantStars;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $restaurantComment;

    /**
     * @ORM\Column(type="integer")
     */
    private $deliveryManStars;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $deliveryManComment;

    /**
     * @ORM\OneToOne(targetEntity=Order::class, inversedBy="rating", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $orderConcerned;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRestaurantStars(): ?int
    {
        return $this->restaurantStars;
    }

    public function setRestaurantStars(int $restaurantStars): self
    {
        $this->restaurantStars = $restaurantStars;

        return $this;
    }

    public function getRestaurantComment(): ?string
    {
        return $this->restaurantComment;
    }

    public function setRestaurantComment(?string $restaurantComment): self
    {
        $this->restaurantComment = $restaurantComment;

        return $this;
    }

    public function getDeliveryManStars(): ?int
    {
        return $this->deliveryManStars;
    }

    public function setDeliveryManStars(int $deliveryManStars): self
    {
        $this->deliveryManStars = $deliveryManStars;

        return $this;
    }

    public function getDeliveryManComment(): ?string
    {
        return $this->deliveryManComment;
    }

    public function setDeliveryManComment(?string $deliveryManComment): self
    {
        $this->deliveryManComment = $deliveryManComment;

        return $this;
    }

    public function getOrderConcerned(): ?Order
    {
        return $this->orderConcerned;
    }

    public function setOrderConcerned(Order $orderConcerned): self
    {
        $this->orderConcerned = $orderConcerned;

        return $this;
    }

}

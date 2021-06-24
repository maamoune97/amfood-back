<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\DeliveryRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=DeliveryRepository::class)
 * @ApiResource()
 */
class Delivery
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"user_read", "orderWrite", "order_read"})
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity=Order::class, inversedBy="delivery", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"user_read"})
     */
    private $command;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"user_read", "orderWrite", "order_read"})
     */
    private $address;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"user_read"})
     */
    private $delivredAt;

    /**
     * @ORM\ManyToOne(targetEntity=City::class, inversedBy="deliveries")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"user_read", "orderWrite", "order_read"})
     */
    private $city;

    /**
     * @ORM\ManyToOne(targetEntity=DeliveryMan::class, inversedBy="deliveries")
     */
    private $deliveryMan;

    /**
     * @ORM\Column(type="string", length=15)
     * @Groups({"orderWrite", "order_read", "user_read"})
     */
    private $longitude;

    /**
     * @ORM\Column(type="string", length=15)
     * @Groups({"orderWrite", "order_read", "user_read"})
     */
    private $latitude;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCommand(): ?Order
    {
        return $this->command;
    }

    public function setCommand(Order $command): self
    {
        $this->command = $command;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getDelivredAt(): ?\DateTimeInterface
    {
        return $this->delivredAt;
    }

    public function setDelivredAt(?\DateTimeInterface $delivredAt): self
    {
        $this->delivredAt = $delivredAt;

        return $this;
    }

    public function getCity(): ?City
    {
        return $this->city;
    }

    public function setCity(?City $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getDeliveryMan(): ?DeliveryMan
    {
        return $this->deliveryMan;
    }

    public function setDeliveryMan(?DeliveryMan $deliveryMan): self
    {
        $this->deliveryMan = $deliveryMan;

        return $this;
    }

    public function getLongitude(): ?string
    {
        return $this->longitude;
    }

    public function setLongitude(string $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getLatitude(): ?string
    {
        return $this->latitude;
    }

    public function setLatitude(string $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }
}

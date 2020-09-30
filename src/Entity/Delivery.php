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
     * @Groups({"user_read"})
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
     * @Groups({"user_read"})
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=15)
     * @Groups({"user_read"})
     */
    private $status;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"user_read"})
     */
    private $delivredAt;

    /**
     * @ORM\ManyToOne(targetEntity=City::class, inversedBy="deliveries")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"user_read"})
     */
    private $city;

    /**
     * @ORM\ManyToOne(targetEntity=DeliveryMan::class, inversedBy="deliveries")
     * @ORM\JoinColumn(nullable=false)
     */
    private $deliveryMan;

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

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

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
}

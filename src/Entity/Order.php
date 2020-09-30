<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\OrderRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=OrderRepository::class)
 * @ORM\Table(name="`order`")
 * @ApiResource(
 * denormalizationContext={"groups"={"orderWrite"}}
 * )
 */
class Order
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"user_read", "orderWrite"})
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="orders")
     * @ORM\JoinColumn(nullable=false)
     */
    private $customer;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"user_read", "orderWrite"})
     */
    private $createdAt;

    /**
     * @ORM\Column(type="string", length=15)
     * @Groups({"user_read", "orderWrite"})
     */
    private $status;

    /**
     * @ORM\OneToOne(targetEntity=Delivery::class, mappedBy="command", cascade={"persist", "remove"})
     */
    private $delivery;

    /**
     * @ORM\OneToOne(targetEntity=Rating::class, mappedBy="orderConcerned", cascade={"persist", "remove"})
     */
    private $rating;

    public function __construct()
    {

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCustomer(): ?User
    {
        return $this->customer;
    }

    public function setCustomer(?User $customer): self
    {
        $this->customer = $customer;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

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

    public function getDelivery(): ?Delivery
    {
        return $this->delivery;
    }

    public function setDelivery(Delivery $delivery): self
    {
        $this->delivery = $delivery;

        // set the owning side of the relation if necessary
        if ($delivery->getCommand() !== $this) {
            $delivery->setCommand($this);
        }

        return $this;
    }

    public function getRating(): ?Rating
    {
        return $this->rating;
    }

    public function setRating(Rating $rating): self
    {
        $this->rating = $rating;

        // set the owning side of the relation if necessary
        if ($rating->getOrderConcerned() !== $this) {
            $rating->setOrderConcerned($this);
        }

        return $this;
    }
}

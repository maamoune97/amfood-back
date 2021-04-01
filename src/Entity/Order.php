<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\OrderRepository;
use ApiPlatform\Core\Annotation\ApiFilter;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

/**
 * @ORM\Entity(repositoryClass=OrderRepository::class)
 * @ORM\Table(name="`order`")
 * @ApiResource(
 *  denormalizationContext={"groups"={"orderWrite"}},
 *  normalizationContext={"groups"={"order_read"}}
 * )
 * @ApiFilter(SearchFilter::class, properties={"status", "delivery.city"})
 */
class Order
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"user_read", "orderWrite", "order_read"})
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="orders")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"order_read", "order_read"})
     */
    private $customer;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"user_read", "orderWrite", "order_read"})
     */
    private $createdAt;

    /**
     * @ORM\Column(type="string", length=15)
     * @Groups({"user_read", "orderWrite", "order_read"})
     */
    private $status;

    /**
     * @ORM\OneToOne(targetEntity=Delivery::class, mappedBy="command", cascade={"persist", "remove"})
     * @Groups({"orderWrite", "order_read", "user_read"})
     */
    private $delivery;

    /**
     * @ORM\OneToOne(targetEntity=Rating::class, mappedBy="orderConcerned", cascade={"persist", "remove"})
     */
    private $rating;

    /**
     * @ORM\OneToMany(targetEntity=OrderArticlePack::class, mappedBy="command", orphanRemoval=true)
     * @Groups({"user_read", "orderWrite", "order_read"})
     */
    private $orderArticlePacks;

    /**
     * @ORM\ManyToOne(targetEntity=Restaurant::class, inversedBy="orders")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"orderWrite", "order_read", "user_read"})
     */
    private $restaurant;

    public function __construct()
    {
        $this->orderArticlePacks = new ArrayCollection();
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

    /**
     * @return Collection|OrderArticlePack[]
     */
    public function getOrderArticlePacks(): Collection
    {
        return $this->orderArticlePacks;
    }

    public function addOrderArticlePack(OrderArticlePack $orderArticlePack): self
    {
        if (!$this->orderArticlePacks->contains($orderArticlePack)) {
            $this->orderArticlePacks[] = $orderArticlePack;
            $orderArticlePack->setCommand($this);
        }

        return $this;
    }

    public function removeOrderArticlePack(OrderArticlePack $orderArticlePack): self
    {
        if ($this->orderArticlePacks->contains($orderArticlePack)) {
            $this->orderArticlePacks->removeElement($orderArticlePack);
            // set the owning side to null (unless already changed)
            if ($orderArticlePack->getCommand() === $this) {
                $orderArticlePack->setCommand(null);
            }
        }

        return $this;
    }

    public function getRestaurant(): ?Restaurant
    {
        return $this->restaurant;
    }

    public function setRestaurant(?Restaurant $restaurant): self
    {
        $this->restaurant = $restaurant;

        return $this;
    }

    public function getTotalPrice() : float
    {
        $somme = 0;

        foreach ($this->getOrderArticlePacks() as $orderArticlePack) 
        {
            $somme += $orderArticlePack->getArticle()->getPrice();
            
            foreach ($orderArticlePack->getOptionFieldsTaken() as $optionFieldTaken) {
                $somme += $optionFieldTaken->getAdditionalPrice();
            }
        }

        return $somme;
    }
}

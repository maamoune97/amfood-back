<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\DeliveryManRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=DeliveryManRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class DeliveryMan
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"user_read", "order_refused_read", "delivery_read", "order_read"})
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity=User::class, inversedBy="deliveryMan", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"order_read"})
     */
    private $user;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"user_read"})
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity=City::class, inversedBy="deliveryMen")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"user_read"})
     */
    private $city;
    
    /**
     * sert à afficher le champ de recherche pour trouver si un numéro existe
     *
     * @var string
     */
    public $phone;

    /**
     * @ORM\OneToMany(targetEntity=Delivery::class, mappedBy="deliveryMan")
     * @Groups({"user_read"})
     */
    private $deliveries;

    /**
     * @ORM\OneToMany(targetEntity=OrderRefused::class, mappedBy="deliveryMan")
     */
    private $ordersRefused;

    public function __construct()
    {
        $this->deliveries = new ArrayCollection();
        $this->ordersRefused = new ArrayCollection();
    }

    /**
     * @ORM\PrePersist
     *
     * @return void
     */
    public function initializeCreateAt()
    {
        if (!$this->createdAt) {
            $this->setCreatedAt(new DateTime());
        }
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

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

    public function getCity(): ?City
    {
        return $this->city;
    }

    public function setCity(?City $city): self
    {
        $this->city = $city;

        return $this;
    }

    /**
     * @return Collection|Delivery[]
     */
    public function getDeliveries(): Collection
    {
        return $this->deliveries;
    }

    public function addDelivery(Delivery $delivery): self
    {
        if (!$this->deliveries->contains($delivery)) {
            $this->deliveries[] = $delivery;
            $delivery->setDeliveryMan($this);
        }

        return $this;
    }

    public function removeDelivery(Delivery $delivery): self
    {
        if ($this->deliveries->contains($delivery)) {
            $this->deliveries->removeElement($delivery);
            // set the owning side to null (unless already changed)
            if ($delivery->getDeliveryMan() === $this) {
                $delivery->setDeliveryMan(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|OrderRefused[]
     */
    public function getOrdersRefused(): Collection
    {
        return $this->ordersRefused;
    }

    public function addOrdersRefused(OrderRefused $ordersRefused): self
    {
        if (!$this->ordersRefused->contains($ordersRefused)) {
            $this->ordersRefused[] = $ordersRefused;
            $ordersRefused->setDeliveryMan($this);
        }

        return $this;
    }

    public function removeOrdersRefused(OrderRefused $ordersRefused): self
    {
        if ($this->ordersRefused->contains($ordersRefused)) {
            $this->ordersRefused->removeElement($ordersRefused);
            // set the owning side to null (unless already changed)
            if ($ordersRefused->getDeliveryMan() === $this) {
                $ordersRefused->setDeliveryMan(null);
            }
        }

        return $this;
    }
}

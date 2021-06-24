<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\OrderRefusedRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Symfony\Component\Serializer\Annotation\Groups;

//order refused by delivery man

/**
 * @HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass=OrderRefusedRepository::class)
 * @ApiResource(
 *  normalizationContext={"groups"={"order_refused_read"}},
 * )
 */
class OrderRefused
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"user_read", "order_refused_read"})
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Order::class, inversedBy="refuseds")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"user_read", "order_refused_read"})
     */
    private $command;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"user_read", "order_refused_read"})
     */
    private $refusedAt;

    /**
     * @ORM\ManyToOne(targetEntity=DeliveryMan::class, inversedBy="ordersRefused")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"order_refused_read"})
     */
    private $deliveryMan;

    /**
     * @ORM\PrePersist
     *
     * @return void
     */
    public function initializeRefusedAt()
    {
        if (!$this->getRefusedAt())
        {
            $this->setRefusedAt(new DateTime());
        }
    }

    public function __construct()
    {
        
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCommand(): ?Order
    {
        return $this->command;
    }

    public function setCommand(?Order $command): self
    {
        $this->command = $command;

        return $this;
    }

    public function getRefusedAt(): ?\DateTimeInterface
    {
        return $this->refusedAt;
    }

    public function setRefusedAt(\DateTimeInterface $refusedAt): self
    {
        $this->refusedAt = $refusedAt;

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

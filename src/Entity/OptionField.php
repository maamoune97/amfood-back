<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\OptionFieldRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=OptionFieldRepository::class)
 * @ApiResource(
 * normalizationContext={"groups"={"option_field_read"}}
 * )
 */
class OptionField
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"orderWrite", "option_field_read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"orderWrite", "option_field_read"})
     */
    private $name;

    /**
     * @ORM\Column(type="float")
     * @Groups({"orderWrite", "option_field_read"})
     */
    private $additionalPrice;

    /**
     * @ORM\ManyToOne(targetEntity=Option::class, inversedBy="optionFields")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"orderWrite", "option_field_read"})
     */
    private $myOption;

    /**
     * @ORM\ManyToMany(targetEntity=Order::class, mappedBy="optionFieldsChosen")
     */
    private $orders;

    public function __construct()
    {
        $this->orders = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getAdditionalPrice(): ?float
    {
        return $this->additionalPrice;
    }

    public function setAdditionalPrice(float $additionalPrice): self
    {
        $this->additionalPrice = $additionalPrice;

        return $this;
    }

    public function getMyOption(): ?Option
    {
        return $this->myOption;
    }

    public function setMyOption(?Option $myOption): self
    {
        $this->myOption = $myOption;

        return $this;
    }

    /**
     * @return Collection|Order[]
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function addOrder(Order $order): self
    {
        if (!$this->orders->contains($order)) {
            $this->orders[] = $order;
            $order->addOptionFieldsChosen($this);
        }

        return $this;
    }

    public function removeOrder(Order $order): self
    {
        if ($this->orders->contains($order)) {
            $this->orders->removeElement($order);
            $order->removeOptionFieldsChosen($this);
        }

        return $this;
    }
}

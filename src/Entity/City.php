<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\CityRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=CityRepository::class)
 * @UniqueEntity(
 *     fields={"name", "island"},
 *     errorPath="name",
 *     message="Cette ville est déjà enregistré dans cette île"
 * )
 * @ApiResource(
 * collectionOperations={"get"},
 * itemOperations={"get"}
 * )
 */
class City
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"island_read", "user_read", "orderWrite"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\NotNull(message="Entrez le nom de la ville")
     * @Groups({"island_read", "user_read"})
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity=Island::class, inversedBy="cities")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotNull(message="Sélectionnez l'ile de la ville")
     */
    private $island;

    /**
     * @ORM\OneToMany(targetEntity=Restaurant::class, mappedBy="location", orphanRemoval=true)
     * @ApiSubresource
     */
    private $restaurants;

    /**
     * @ORM\OneToMany(targetEntity=Delivery::class, mappedBy="city")
     */
    private $deliveries;

    /**
     * @ORM\OneToMany(targetEntity=DeliveryMan::class, mappedBy="region")
     */
    private $deliveryMen;

    public function __construct()
    {
        $this->restaurants = new ArrayCollection();
        $this->deliveries = new ArrayCollection();
        $this->deliveryMen = new ArrayCollection();
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

    public function getIsland(): ?Island
    {
        return $this->island;
    }

    public function setIsland(?Island $island): self
    {
        $this->island = $island;

        return $this;
    }

    /**
     * @return Collection|Restaurant[]
     */
    public function getRestaurants(): Collection
    {
        return $this->restaurants;
    }

    public function addRestaurant(Restaurant $restaurant): self
    {
        if (!$this->restaurants->contains($restaurant)) {
            $this->restaurants[] = $restaurant;
            $restaurant->setLocation($this);
        }

        return $this;
    }

    public function removeRestaurant(Restaurant $restaurant): self
    {
        if ($this->restaurants->contains($restaurant)) {
            $this->restaurants->removeElement($restaurant);
            // set the owning side to null (unless already changed)
            if ($restaurant->getLocation() === $this) {
                $restaurant->setLocation(null);
            }
        }

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
            $delivery->setCity($this);
        }

        return $this;
    }

    public function removeDelivery(Delivery $delivery): self
    {
        if ($this->deliveries->contains($delivery)) {
            $this->deliveries->removeElement($delivery);
            // set the owning side to null (unless already changed)
            if ($delivery->getCity() === $this) {
                $delivery->setCity(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|DeliveryMan[]
     */
    public function getDeliveryMen(): Collection
    {
        return $this->deliveryMen;
    }

    public function addDeliveryMan(DeliveryMan $deliveryMan): self
    {
        if (!$this->deliveryMen->contains($deliveryMan)) {
            $this->deliveryMen[] = $deliveryMan;
            $deliveryMan->setCity($this);
        }

        return $this;
    }

    public function removeDeliveryMan(DeliveryMan $deliveryMan): self
    {
        if ($this->deliveryMen->contains($deliveryMan)) {
            $this->deliveryMen->removeElement($deliveryMan);
            // set the owning side to null (unless already changed)
            if ($deliveryMan->getCity() === $this) {
                $deliveryMan->setCity(null);
            }
        }

        return $this;
    }
}

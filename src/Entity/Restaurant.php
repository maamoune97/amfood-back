<?php

namespace App\Entity;

use DateTime;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\RestaurantRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


/**
 * @ORM\Entity(repositoryClass=RestaurantRepository::class)
 * @HasLifecycleCallbacks()
 * @UniqueEntity("phone", message="ce numéro est déjà associé à un autre restaurant")
 * @UniqueEntity("name", message="ce réstaurant est déjà enregistré")
 * @ApiResource(
 * normalizationContext = {
 *  "groups" = {"restaurant_read"}
 * },
 * collectionOperations={"get"},
 * itemOperations={"get"},
 * subresourceOperations={"api_cities_restaurants_get_subresource" = {
 *      "normalization_context"={"groups" = "restaurants_subresource"}
 *  }
 * }
 * )
 */
class Restaurant
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"restaurants_subresource", "restaurant_read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     * @Groups({"restaurants_subresource", "restaurant_read"})
     * @Assert\NotBlank(message="le nom du réstaurant est obligatoire!")
     * 
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity=City::class, inversedBy="restaurants")
     * @ORM\JoinColumn(nullable=false)
     */
    private $location;

    /**
     * @ORM\Column(type="string", length=20)
     * @Groups({"restaurants_subresource", "restaurant_read"})
     * @Assert\NotBlank(message="le numéro de téléphone du réstaurant est obligatoire!")
     * 
     */
    private $phone;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"restaurants_subresource", "restaurant_read"})
     * * @Assert\Email(
     *     message = "Entrez un adresse email valide!"
     * )
     */
    private $email;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"restaurants_subresource", "restaurant_read"})
     */
    private $activate;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"restaurants_subresource", "restaurant_read"})
     *
     */
    private $imageLogo;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="string", length=100)
     * @Groups({"restaurants_subresource", "restaurant_read"})
     */
    private $speciality;

    /**
     * @ORM\OneToMany(targetEntity=Order::class, mappedBy="restaurant")
     */
    private $orders;

    /**
     * @ORM\OneToOne(targetEntity=RestaurantManager::class, mappedBy="restaurant", cascade={"persist", "remove"})
     */
    private $manager;

    /**
     * @ORM\OneToMany(targetEntity=Section::class, mappedBy="restaurant", orphanRemoval=true)
     * @Groups({"restaurant_read"})
     */
    private $sections;
    
    /**
     * @ORM\PrePersist
     *
     * @return void
     */
    public function initializeActivate()
    {
        $this->setActivate(false);
    }

    /**
     * @ORM\PrePersist
     *
     * @return void
     */
    public function initializeCreatedAt()
    {
        $this->setCreatedAt(new DateTime());
    }

    /**
     * get stars average
     *@Groups({"restaurants_subresource", "restaurant_read"})
     * @return float
     */
    public function getAvgStars() : float
    {
        $totalStars = array_reduce($this->getOrders()->toArray(), function($stars, $order){
            return $stars + $order->getRating()->getRestaurantStars();
        }, 0);
        
        return round($totalStars / count($this->getOrders()), 1, PHP_ROUND_HALF_ODD);
    }
    
    public function __construct()
    {
        $this->menus = new ArrayCollection();
        $this->orders = new ArrayCollection();
        $this->sections = new ArrayCollection();
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

    public function getLocation(): ?City
    {
        return $this->location;
    }

    public function setLocation(?City $location): self
    {
        $this->location = $location;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }


    public function getActivate(): ?bool
    {
        return $this->activate;
    }

    public function setActivate(bool $activate): self
    {
        $this->activate = $activate;

        return $this;
    }

    public function getImageLogo(): ?string
    {
        return $this->imageLogo;
    }

    public function setImageLogo(string $imageLogo): self
    {
        $this->imageLogo = $imageLogo;

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

    public function getSpeciality(): ?string
    {
        return $this->speciality;
    }

    public function setSpeciality(string $speciality): self
    {
        $this->speciality = $speciality;

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
            $order->setRestaurant($this);
        }

        return $this;
    }

    public function removeOrder(Order $order): self
    {
        if ($this->orders->contains($order)) {
            $this->orders->removeElement($order);
            // set the owning side to null (unless already changed)
            if ($order->getRestaurant() === $this) {
                $order->setRestaurant(null);
            }
        }

        return $this;
    }

    public function getManager(): ?RestaurantManager
    {
        return $this->manager;
    }

    public function setManager(RestaurantManager $manager): self
    {
        $this->manager = $manager;

        // set the owning side of the relation if necessary
        if ($manager->getRestaurant() !== $this) {
            $manager->setRestaurant($this);
        }

        return $this;
    }

    /**
     * @return Collection|Section[]
     */
    public function getSections(): Collection
    {
        return $this->sections;
    }

    public function addSection(Section $section): self
    {
        if (!$this->sections->contains($section)) {
            $this->sections[] = $section;
            $section->setRestaurant($this);
        }

        return $this;
    }

    public function removeSection(Section $section): self
    {
        if ($this->sections->contains($section)) {
            $this->sections->removeElement($section);
            // set the owning side to null (unless already changed)
            if ($section->getRestaurant() === $this) {
                $section->setRestaurant(null);
            }
        }

        return $this;
    }

}

<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ArticleRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Filesystem\Filesystem;

/**
 * @ORM\Entity(repositoryClass=ArticleRepository::class)
 * @HasLifecycleCallbacks()
 * @UniqueEntity(
 *     fields={"name", "section"},
 *     errorPath="name",
 *     message="Cet article est déjà enregistré dans cette section"
 * )
 * @ApiResource(
 * collectionOperations={"get"},
 * itemOperations={"get"}
 * )
 */
class Article
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"restaurants_subresource", "orderWrite"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     * @Groups({"restaurants_subresource", "orderWrite"})
     * @Assert\NotNull(message ="Entrez le nom de l'article")
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=500, nullable=true)
     * @Groups({"restaurants_subresource", "orderWrite"})
     */
    private $ingredient;

    /**
     * @ORM\Column(type="float")
     * @Groups({"restaurants_subresource"})
     * @Assert\NotNull(message ="Entrez le prix de l'article")
     */
    private $price;

    /**
     * @ORM\ManyToOne(targetEntity=Section::class, inversedBy="articles")
     * @ORM\JoinColumn(nullable=false)
     */
    private $section;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"restaurants_subresource"})
     */
    private $image;

    /**
     * @ORM\OneToMany(targetEntity=Option::class, mappedBy="article", orphanRemoval=true)
     */
    private $options;

    /**
     * @ORM\ManyToMany(targetEntity=Order::class, mappedBy="articles")
     */
    private $orders;

    /**
     * Remove automaticaly image File when Article was removed
     *@ORM\PostRemove
     * @return void
     */
    public function autoRemoveImageFile()
    {
        $fileSystem = new Filesystem();
        $fileSystem->remove(getcwd().'/media/images/uploads/articles/'.$this->getImage());
    }

    public function __construct()
    {
        $this->options = new ArrayCollection();
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

    public function getIngredient(): ?string
    {
        return $this->ingredient;
    }

    public function setIngredient(?string $ingredient): self
    {
        $this->ingredient = $ingredient;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getSection(): ?Section
    {
        return $this->section;
    }

    public function setSection(?Section $section): self
    {
        $this->section = $section;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return Collection|Option[]
     */
    public function getOptions(): Collection
    {
        return $this->options;
    }

    public function addOption(Option $option): self
    {
        if (!$this->options->contains($option)) {
            $this->options[] = $option;
            $option->setArticle($this);
        }

        return $this;
    }

    public function removeOption(Option $option): self
    {
        if ($this->options->contains($option)) {
            $this->options->removeElement($option);
            // set the owning side to null (unless already changed)
            if ($option->getArticle() === $this) {
                $option->setArticle(null);
            }
        }

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
            $order->addArticle($this);
        }

        return $this;
    }

    public function removeOrder(Order $order): self
    {
        if ($this->orders->contains($order)) {
            $this->orders->removeElement($order);
            $order->removeArticle($this);
        }

        return $this;
    }
}

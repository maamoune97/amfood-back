<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\SectionRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Filesystem\Filesystem;

/**
 * @ORM\Entity(repositoryClass=SectionRepository::class)
 * @HasLifecycleCallbacks()
 * @UniqueEntity(
 *     fields={"name", "restaurant"},
 *     errorPath="name",
 *     message="ce restaurant possède déjà une séction avec ce nom"
 * )
 * @ApiResource(
 * collectionOperations={"get"},
 * itemOperations={"get"}
 * )
 */
class Section
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"restaurant_read", "research_read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     * @Groups({"restaurant_read", "research_read"})
     * @Assert\NotNull(message="Entrez le nom de la séction")
     */
    private $name;

    /**
     * @ORM\Column(type="boolean", options={"default": true})
     * @Groups({"restaurant_read"})
     */
    private $status;

    /**
     * @ORM\OneToMany(targetEntity=Article::class, mappedBy="section", orphanRemoval=true)
     * @Groups({"restaurant_read"})
     */
    private $articles;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"restaurant_read"})
     */
    private $image;

    /**
     * @ORM\ManyToOne(targetEntity=Restaurant::class, inversedBy="sections")
     * @ORM\JoinColumn(nullable=false)
     */
    private $restaurant;

    /**
     * @Groups({"research_read"})
     *
     * @return string
     */
    public function getRestaurantName()
    {
        return $this->getRestaurant()->getName();
    }

    /**
     * Remove automaticaly image File when Section was removed
     *@ORM\PostRemove
     * @return void
     */
    public function autoRemoveImageFile()
    {
        $fileSystem = new Filesystem();
        $fileSystem->remove(getcwd().'/media/images/uploads/sections/'.$this->getImage());
    }

    /**
     * @ORM\PrePersist
     */
    public function initializeStatus()
    {
        if (!$this->status)
        {
            $this->setStatus(1);
        }
    }

    public function __construct()
    {
        $this->articles = new ArrayCollection();
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

    public function getStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return Collection|Article[]
     */
    public function getArticles(): Collection
    {
        return $this->articles;
    }

    public function addArticle(Article $article): self
    {
        if (!$this->articles->contains($article)) {
            $this->articles[] = $article;
            $article->setSection($this);
        }

        return $this;
    }

    public function removeArticle(Article $article): self
    {
        if ($this->articles->contains($article)) {
            $this->articles->removeElement($article);
            // set the owning side to null (unless already changed)
            if ($article->getSection() === $this) {
                $article->setSection(null);
            }
        }

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

    public function getRestaurant(): ?Restaurant
    {
        return $this->restaurant;
    }

    public function setRestaurant(?Restaurant $restaurant): self
    {
        $this->restaurant = $restaurant;

        return $this;
    }
}

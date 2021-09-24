<?php

namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * 
 */
class Research
{
    /**
     * @Groups({"research_read"})
     */
    private $articles;

    /**
     * @Groups({"research_read"})
     */
    private $restaurants;

    /**
     * @Groups({"research_read"})
     */
    private $sections;

    /**
     * @Groups({"research_read"})
     */
    private $specialtyRestaurants;

    public function __construct()
    {
        $this->articles = new ArrayCollection();
        $this->restaurants = new ArrayCollection();
        $this->sections = new ArrayCollection();
        $this->specialtyRestaurants = new ArrayCollection();
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
        }

        return $this;
    }

    public function removeArticle(Article $article): self
    {
        if ($this->articles->contains($article)) {
            $this->articles->removeElement($article);
        }

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
        }

        return $this;
    }

    public function removeRestaurant(Restaurant $restaurant): self
    {
        if ($this->restaurants->contains($restaurant)) {
            $this->restaurants->removeElement($restaurant);
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
        }

        return $this;
    }

    public function removeSection(Section $section): self
    {
        if ($this->sections->contains($section)) {
            $this->sections->removeElement($section);
        }

        return $this;
    }

    /**
     * @return Collection|Restaurant[]
     */
    public function getSpecialtyRestaurants(): Collection
    {
        return $this->specialtyRestaurants;
    }

    public function addSpecialtyRestaurants(Restaurant $restaurant): self
    {
        if (!$this->specialtyRestaurants->contains($restaurant)) {
            $this->specialtyRestaurants[] = $restaurant;
        }

        return $this;
    }

    public function removeSpecialtyRestaurants(Restaurant $restaurant): self
    {
        if ($this->specialtyRestaurants->contains($restaurant)) {
            $this->specialtyRestaurants->removeElement($restaurant);
        }

        return $this;
    }
}

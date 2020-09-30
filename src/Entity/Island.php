<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\IslandRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=IslandRepository::class)
 * @ApiResource(
 * normalizationContext={
 *      "groups" = {"island_read"}
 * },
 * collectionOperations={"get"},
 * itemOperations={"get"}
 * )
 */
class Island
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"island_read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=45)
     * @Groups({"island_read"})
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=City::class, mappedBy="island", orphanRemoval=true)
     * @Groups({"island_read"})
     */
    private $cities;

    public function __construct()
    {
        $this->cities = new ArrayCollection();
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

    /**
     * @return Collection|City[]
     */
    public function getCities(): Collection
    {
        return $this->cities;
    }

    public function addCity(City $city): self
    {
        if (!$this->cities->contains($city)) {
            $this->cities[] = $city;
            $city->setIsland($this);
        }

        return $this;
    }

    public function removeCity(City $city): self
    {
        if ($this->cities->contains($city)) {
            $this->cities->removeElement($city);
            // set the owning side to null (unless already changed)
            if ($city->getIsland() === $this) {
                $city->setIsland(null);
            }
        }

        return $this;
    }
}

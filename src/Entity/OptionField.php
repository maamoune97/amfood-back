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
 */
class OptionField
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"orderWrite", "article_read", "user_read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"article_read", "user_read"})
     */
    private $name;

    /**
     * @ORM\Column(type="float")
     * @Groups({"article_read", "user_read"})
     */
    private $additionalPrice;

    /**
     * @ORM\ManyToOne(targetEntity=Option::class, inversedBy="optionFields")
     * @ORM\JoinColumn(nullable=false)
     */
    private $myOption;

    /**
     * @ORM\OneToMany(targetEntity=OrderArticlePack::class, mappedBy="optionField")
     */
    private $orderArticlePacks;

    public function __construct()
    {
        $this->orderArticlePacks = new ArrayCollection();
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
            $orderArticlePack->setOptionField($this);
        }

        return $this;
    }

    public function removeOrderArticlePack(OrderArticlePack $orderArticlePack): self
    {
        if ($this->orderArticlePacks->contains($orderArticlePack)) {
            $this->orderArticlePacks->removeElement($orderArticlePack);
            // set the owning side to null (unless already changed)
            if ($orderArticlePack->getOptionField() === $this) {
                $orderArticlePack->setOptionField(null);
            }
        }

        return $this;
    }
}

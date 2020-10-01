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
     * @Groups({"option_field_read"})
     */
    private $name;

    /**
     * @ORM\Column(type="float")
     * @Groups({"option_field_read"})
     */
    private $additionalPrice;

    /**
     * @ORM\ManyToOne(targetEntity=Option::class, inversedBy="optionFields")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"option_field_read"})
     */
    private $myOption;

    /**
     * @ORM\OneToMany(targetEntity=ArticlePack::class, mappedBy="optionField")
     */
    private $articlePacks;

    public function __construct()
    {
        $this->articlePacks = new ArrayCollection();
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
     * @return Collection|ArticlePack[]
     */
    public function getArticlePacks(): Collection
    {
        return $this->articlePacks;
    }

    public function addArticlePack(ArticlePack $articlePack): self
    {
        if (!$this->articlePacks->contains($articlePack)) {
            $this->articlePacks[] = $articlePack;
            $articlePack->setOptionField($this);
        }

        return $this;
    }

    public function removeArticlePack(ArticlePack $articlePack): self
    {
        if ($this->articlePacks->contains($articlePack)) {
            $this->articlePacks->removeElement($articlePack);
            // set the owning side to null (unless already changed)
            if ($articlePack->getOptionField() === $this) {
                $articlePack->setOptionField(null);
            }
        }

        return $this;
    }
}

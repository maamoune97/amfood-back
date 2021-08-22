<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\OrderArticlePackRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=OrderArticlePackRepository::class)
 * @ApiResource(
 * normalizationContext={"groups"={"order_article_pack_read"}}
 * )
 */
class OrderArticlePack
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"orderWrite", "user_read", "order_read"})
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Article::class, inversedBy="orderArticlePacks")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"orderWrite", "user_read", "order_read"})
     */
    private $article;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"orderWrite", "user_read", "order_read"})
     */
    private $quantity;

    /**
     * @ORM\ManyToOne(targetEntity=Order::class, inversedBy="orderArticlePacks")
     * @ORM\JoinColumn(nullable=false)
     */
    private $command;

    /**
     * @ORM\ManyToMany(targetEntity=OptionField::class, inversedBy="orderArticlePacks", cascade={"persist"})
     * @Groups({"orderWrite", "user_read", "order_read"})
     */
    private $optionFieldsTaken;

    public function __construct()
    {
        $this->optionFieldsTaken = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getArticle(): ?Article
    {
        return $this->article;
    }

    public function setArticle(?Article $article): self
    {
        $this->article = $article;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
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

    /**
     * @return Collection|OptionField[]
     */
    public function getOptionFieldsTaken(): Collection
    {
        return $this->optionFieldsTaken;
    }

    public function addOptionFieldsTaken(OptionField $optionFieldsTaken): self
    {
        if (!$this->optionFieldsTaken->contains($optionFieldsTaken)) {
            $this->optionFieldsTaken[] = $optionFieldsTaken;
        }

        return $this;
    }

    public function removeOptionFieldsTaken(OptionField $optionFieldsTaken): self
    {
        if ($this->optionFieldsTaken->contains($optionFieldsTaken)) {
            $this->optionFieldsTaken->removeElement($optionFieldsTaken);
        }

        return $this;
    }

    /**
     * get sub total price of current pack
     *
     * @Groups({"user_read", "order_read"})
     * 
     * @return float
     */
    public function getSubTotalPrice(): float
    {
        $subTotal = $this->getArticle()->getPrice();
        
        foreach ($this->getOptionFieldsTaken() as $optionField)
        {
            $subTotal += $optionField->getAdditionalPrice();
        }

        return $subTotal * $this->getQuantity();
    }
}

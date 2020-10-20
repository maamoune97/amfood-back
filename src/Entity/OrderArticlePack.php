<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\OrderArticlePackRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=OrderArticlePackRepository::class)
 * @ApiResource(
 * normalizationContext={"groups"={"orderWrite"}}
 * )
 */
class OrderArticlePack
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"orderWrite", "user_read"})
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Article::class, inversedBy="orderArticlePacks")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"orderWrite", "user_read"})
     */
    private $article;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"orderWrite", "user_read"})
     */
    private $quantity;

    /**
     * @ORM\ManyToOne(targetEntity=Order::class, inversedBy="orderArticlePacks")
     * @ORM\JoinColumn(nullable=false)
     */
    private $command;

    /**
     * @ORM\ManyToOne(targetEntity=OptionField::class, inversedBy="orderArticlePacks")
     * @Groups({"orderWrite", "user_read"})
     */
    private $optionField;

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

    public function getOptionField(): ?OptionField
    {
        return $this->optionField;
    }

    public function setOptionField(?OptionField $optionField): self
    {
        $this->optionField = $optionField;

        return $this;
    }
}

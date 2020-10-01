<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ArticlePackRepository;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=ArticlePackRepository::class)
 * @ApiResource(
 * normalizationContext={"groups"={"orderWrite"}}
 * )
 */
class ArticlePack
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"orderWrite"})
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Article::class, inversedBy="articlePacks")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"orderWrite"})
     */
    private $article;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"orderWrite"})
     */
    private $quantity;

    /**
     * @ORM\ManyToOne(targetEntity=Order::class, inversedBy="articlePacks")
     * @ORM\JoinColumn(nullable=false)
     */
    private $command;

    /**
     * @ORM\ManyToOne(targetEntity=OptionField::class, inversedBy="articlePacks")
     * @Groups({"orderWrite"})
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

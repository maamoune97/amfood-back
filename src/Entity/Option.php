<?php

namespace App\Entity;

use App\Repository\OptionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OptionRepository::class)
 * @ORM\Table(name="`option`")
 */
class Option
{
    const FRENCH_TYPE = [
        'checkbox' => 'Facultative',
        'radio' => 'Obligatoire',
    ];

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity=Article::class, inversedBy="options")
     * @ORM\JoinColumn(nullable=false)
     */
    private $article;

    /**
     * @ORM\OneToMany(targetEntity=OptionField::class, mappedBy="myOption", orphanRemoval=true)
     */
    private $optionFields;

    public function __construct()
    {
        $this->optionFields = new ArrayCollection();
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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getFrenchType(): ?string
    {
        return self::FRENCH_TYPE[$this->type];
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

    /**
     * @return Collection|OptionField[]
     */
    public function getOptionFields(): Collection
    {
        return $this->optionFields;
    }

    public function addOptionField(OptionField $optionField): self
    {
        if (!$this->optionFields->contains($optionField)) {
            $this->optionFields[] = $optionField;
            $optionField->setMyOption($this);
        }

        return $this;
    }

    public function removeOptionField(OptionField $optionField): self
    {
        if ($this->optionFields->contains($optionField)) {
            $this->optionFields->removeElement($optionField);
            // set the owning side to null (unless already changed)
            if ($optionField->getMyOption() === $this) {
                $optionField->setMyOption(null);
            }
        }

        return $this;
    }
}

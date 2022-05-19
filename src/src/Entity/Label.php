<?php

namespace App\Entity;

use App\Repository\LabelRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=LabelRepository::class)
 */
class Label
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank(message="labels.store.name.blank")
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @Assert\Valid()
     * @ORM\OneToMany(targetEntity=PersonLabel::class, mappedBy="label")
     */
    private $personLabel;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="string", length=255)
     */
    private $color;

    /**
     * @Assert\NotNull()
     * @ORM\Column(type="string", length=255)
     */
    private $icon;

    /**
     * @Assert\Length(min=10, max=140, minMessage="labels.store.icon.description.min", maxMessage="labels.store.icon.description.max")
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    public function __construct()
    {
        $this->personLabel = new ArrayCollection();
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
     * @return Collection|PersonLabel[]
     */
    public function getPersonLabel(): Collection
    {
        return $this->personLabel;
    }

    public function addPersonLabel(PersonLabel $personLabel): self
    {
        if (!$this->personLabel->contains($personLabel)) {
            $this->personLabel[] = $personLabel;
            $personLabel->setLabel($this);
        }

        return $this;
    }

    public function removePersonLabel(PersonLabel $personLabel): self
    {
        if ($this->personLabel->removeElement($personLabel)) {
            // set the owning side to null (unless already changed)
            if ($personLabel->getLabel() === $this) {
                $personLabel->setLabel(null);
            }
        }

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(string $color): self
    {
        $this->color = $color;

        return $this;
    }

    public function getIcon(): ?string
    {
        return $this->icon;
    }

    public function setIcon(string $icon): self
    {
        $this->icon = $icon;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }


}

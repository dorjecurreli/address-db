<?php

namespace App\Entity;

use App\Repository\OrganizationTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=OrganizationTypeRepository::class)
 *
 * @UniqueEntity("name")
 */
class OrganizationType
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotNull(message="organizations.store.organization_type.null")
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     *
     * @ORM\ManyToOne(targetEntity=OrganizationType::class, inversedBy="childs", cascade={"persist"})
     */
    private $parent;

    /**
     *
     * @ORM\OneToMany (targetEntity=OrganizationType::class, mappedBy="parent", cascade={"persist"})
     */
    private $childs;

    /**
     * @Assert\Valid()
     * @ORM\OneToMany(targetEntity=OrganizationOrganizationType::class, mappedBy="organizationType", cascade={"persist"})
     */
    private $organizationOrganizationType;



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
     * @return Collection|OrganizationOrganizationType[]
     */
    public function getOrganizationOrganizationType(): Collection
    {
        return $this->organizationOrganizationType;
    }

    public function addOrganizationOrganizationType(OrganizationOrganizationType $organizationOrganizationType): self
    {
        if (!$this->organizationOrganizationType->contains($organizationOrganizationType)) {
            $this->organizationOrganizationType[] = $organizationOrganizationType;
            $organizationOrganizationType->setOrganizationType($this);
        }

        return $this;
    }

    public function removeOrganizationOrganizationType(OrganizationOrganizationType $organizationOrganizationType): self
    {
        if ($this->organizationOrganizationType->removeElement($organizationOrganizationType)) {
            // set the owning side to null (unless already changed)
            if ($organizationOrganizationType->getOrganizationType() === $this) {
                $organizationOrganizationType->setOrganizationType(null);
            }
        }

        return $this;
    }


    /**
     * @return mixed
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param mixed $parent
     * @return OrganizationType
     */
    public function setParent($parent)
    {
        $this->parent = $parent;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getChilds()
    {
        return $this->childs;
    }

    /**
     * @param mixed $childs
     * @return OrganizationType
     */
    public function setChilds($childs)
    {
        $this->childs = $childs;
        return $this;
    }

}

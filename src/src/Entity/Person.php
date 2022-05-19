<?php

namespace App\Entity;

use App\Repository\PersonRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=PersonRepository::class)
 */
class Person
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="organizations.store.first_name.null")
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="organizations.store.last_name.null")
     */
    private $lastName;

    /**
     * @Assert\Length(min=10, max=140)
     * @ORM\Column(type="text", length=255)
     */
    private $salutation;


    /**
     * @Assert\Valid()
     * @ORM\OneToMany(targetEntity=PersonLabel::class, mappedBy="person", cascade={"persist"})
     */
    private $personLabel;

    /**
     * @Assert\Valid()
     * @ORM\ManyToOne(targetEntity=Organization::class, inversedBy="person", cascade={"persist"})
     */
    private $organization;

    /**
     * @Assert\Valid()
     * @ORM\ManyToOne(targetEntity=Address::class, inversedBy="persons", cascade={"persist"})
     */
    private $address;


    public function __construct()
    {
        $this->personLabel = new ArrayCollection();
        $this->address = new ArrayCollection();
        $this->addresses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

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
            $personLabel->setPerson($this);
        }

        return $this;
    }

    public function removePersonLabel(PersonLabel $personLabel): self
    {
        if ($this->personLabel->removeElement($personLabel)) {
            // set the owning side to null (unless already changed)
            if ($personLabel->getPerson() === $this) {
                $personLabel->setPerson(null);
            }
        }

        return $this;
    }


    public function getOrganization(): ?Organization
    {
        return $this->organization;
    }

    public function setOrganization(?Organization $organization): self
    {
        $this->organization = $organization;

        return $this;
    }

    public function getAddress(): ?Address
    {
        return $this->address;
    }

    public function setAddress(?Address $address): self
    {
        $this->address = $address;

        return $this;
    }


    public function getSalutation(): ?string
    {
        return $this->salutation;
    }

    public function setSalutation(string $salutation): self
    {
        $this->salutation = $salutation;

        return $this;
    }




}

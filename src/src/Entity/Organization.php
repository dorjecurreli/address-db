<?php

namespace App\Entity;

use App\Repository\OrganizationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinTable;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=OrganizationRepository::class)
 */
class Organization
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank(message="organizations.store.name.blank")
     * @Assert\Length(min=2, minMessage="organizations.store.name.length", maxMessage="organizations.store.name.length")
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @Assert\Valid
     * @ORM\ManyToOne(targetEntity=Address::class, inversedBy="organizations", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     *
     */
    private $address;

    /**
     * @Assert\Valid
     * @ORM\ManyToOne(targetEntity=ContactDetail::class, inversedBy="organization", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $contactDetail;

    /**
     * @ORM\OneToMany(targetEntity=Person::class, mappedBy="organization")
     *
     */
    private $person;


    /**
     * @Assert\Valid()
     * @ORM\OneToMany(targetEntity=OrganizationOrganizationType::class, mappedBy="organization", cascade={"persist"})
     */
    private $organizationOrganizationType;



    public function __construct()
    {
        $this->person = new ArrayCollection();
        $this->organizationOrganizationType = new ArrayCollection();
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

    public function getAddress(): ?Address
    {
        return $this->address;
    }

    public function setAddress(?Address $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getContactDetail(): ?ContactDetail
    {
        return $this->contactDetail;
    }

    public function setContactDetail(?ContactDetail $contactDetail): self
    {
        $this->contactDetail = $contactDetail;

        return $this;
    }

    /**
     * @return Collection|Person[]
     */
    public function getPerson(): Collection
    {
        return $this->person;
    }

    public function addPerson(Person $person): self
    {
        if (!$this->person->contains($person)) {
            $this->person[] = $person;
            $person->setOrganization($this);
        }

        return $this;
    }

    public function removePerson(Person $person): self
    {
        if ($this->person->removeElement($person)) {
            // set the owning side to null (unless already changed)
            if ($person->getOrganization() === $this) {
                $person->setOrganization(null);
            }
        }

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
            $organizationOrganizationType->setOrganization($this);
        }

        return $this;
    }

    public function removeOrganizationOrganizationType(OrganizationOrganizationType $organizationOrganizationType): self
    {
        if ($this->organizationOrganizationType->removeElement($organizationOrganizationType)) {
            // set the owning side to null (unless already changed)
            if ($organizationOrganizationType->getOrganization() === $this) {
                $organizationOrganizationType->setOrganization(null);
            }
        }

        return $this;
    }


}

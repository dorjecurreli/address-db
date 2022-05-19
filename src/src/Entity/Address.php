<?php

namespace App\Entity;

use App\Repository\AddressRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=AddressRepository::class)
 */
class Address
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;


//    /**
//     * @Assert\Valid
//     * @ORM\ManyToOne(targetEntity=Country::class, inversedBy="addresses", cascade={"persist"})
//     * @ORM\JoinColumn(nullable=false)
//     */
//    private $country;



    /**
     * @Assert\NotNull
     * @ORM\Column(type="string", length=255)
     *
     */
    private $country;


    /**
     * @Assert\NotNull(message="organizations.store.region.null")
     * @ORM\Column(type="string", length=255)
     */
    private $region;


    /**
     * @Assert\NotNull(message="organizations.store.city.null")
     * @ORM\Column(type="string", length=255)
     */
    private $city;

    /**
     * @Assert\NotNull(message="organizations.store.street.null")
     * @Assert\Length(min=4, minMessage="test", maxMessage="test")
     * @ORM\Column(type="string", length=255)
     */
    private $street;

    /**
     * @Assert\NotNull(message="organizations.store.house_number.null")
     * @ORM\Column(type="string", length=255)
     */
    private $houseNumber;

    /**
     * @Assert\NotNull(message="organizations.postal_code.invalid")
     * @ORM\Column(type="string", length=255)
     */
    private $postalCode;

    /**
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="address")
     */
    private $users;

    /**
     * @ORM\OneToMany(targetEntity=Organization::class, mappedBy="address")
     */
    private $organizations;

    /**
     * @ORM\OneToMany(targetEntity=Person::class, mappedBy="address")
     */
    private $persons;


    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->organizations = new ArrayCollection();
        $this->persons = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getRegion(): ?string
    {
        return $this->region;
    }

    public function setRegion(string $region): self
    {
        $this->region = $region;

        return $this;
    }

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function setStreet(string $street): self
    {
        $this->street = $street;

        return $this;
    }

    public function getHouseNumber(): ?string
    {
        return $this->houseNumber;
    }

    public function setHouseNumber(string $houseNumber): self
    {
        $this->houseNumber = $houseNumber;

        return $this;
    }

    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    public function setPostalCode(string $postalCode): self
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setAddress($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getAddress() === $this) {
                $user->setAddress(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Organization[]
     */
    public function getOrganizations(): Collection
    {
        return $this->organizations;
    }

    public function addOrganization(Organization $organization): self
    {
        if (!$this->organizations->contains($organization)) {
            $this->organizations[] = $organization;
            $organization->addAddress($this);
        }

        return $this;
    }

    public function removeOrganization(Organization $organization): self
    {
        if ($this->organizations->removeElement($organization)) {
            $organization->removeAddress($this);
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param mixed $city
     */
    public function setCity($city): void
    {
        $this->city = $city;
    }


    public function __toString()
    {
        return (string)$this->id;
    }

    public function getCountry()
    {
        return $this->country;
    }

    public function setCountry($country): void
    {
        $this->country = $country;

    }

    /**
     * @return Collection|Person[]
     */
    public function getPersons(): Collection
    {
        return $this->persons;
    }

    public function addPerson(Person $person): self
    {
        if (!$this->persons->contains($person)) {
            $this->persons[] = $person;
            $person->setAddress($this);
        }

        return $this;
    }

    public function removePerson(Person $person): self
    {
        if ($this->persons->removeElement($person)) {
            // set the owning side to null (unless already changed)
            if ($person->getAddress() === $this) {
                $person->setAddress(null);
            }
        }

        return $this;
    }





}

<?php

namespace App\Entity;

use App\Repository\ContactDetailRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ContactDetailRepository::class)
 */
class ContactDetail
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotNull(message="organizations.store.telephone_number.null")
     * @Assert\Regex(pattern="^\s*(?:\+?(\d{1,3}))?[-. (]*(\d{3})[-. )]*(\d{3})[-. ]*(\d{4})(?: *x(\d+))?\s*$^", message="organizations.store.telephone_number.invalid")
     * @ORM\Column(type="string", length=255)
     */
    private $telephoneNumber;

    /**
     * @Assert\NotNull(message="organizations.store.fax.null")
     * @Assert\Regex(pattern="^\s*(?:\+?(\d{1,3}))?[-. (]*(\d{3})[-. )]*(\d{3})[-. ]*(\d{4})(?: *x(\d+))?\s*$^", message="organizations.fax.invalid")
     * @ORM\Column(type="string", length=255)
     */
    private $fax;

    /**
     *
     * @Assert\Regex(pattern="@^(http\:\/\/|https\:\/\/)?([a-z0-9][a-z0-9\-]*\.)+[a-z0-9][a-z0-9\-]*$@i", message="organizations.internet_site.invalid")
     * @ORM\Column(type="string", length=255)
     */
    private $internetSite;

    /**
     * @Assert\Email(message="organizations.store.email")
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\OneToMany(targetEntity=Organization::class, mappedBy="contactDetail")
     */
    private $organization;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $modified_at;

    public function __construct()
    {
        $this->organization = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTelephoneNumber(): ?string
    {
        return $this->telephoneNumber;
    }

    public function setTelephoneNumber(string $telephoneNumber): self
    {
        $this->telephoneNumber = $telephoneNumber;

        return $this;
    }

    public function getFax(): ?string
    {
        return $this->fax;
    }

    public function setFax(string $fax): self
    {
        $this->fax = $fax;

        return $this;
    }

    public function getInternetSite(): ?string
    {
        return $this->internetSite;
    }

    public function setInternetSite(string $internetSite): self
    {
        $this->internetSite = $internetSite;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return Collection|Organization[]
     */
    public function getOrganization(): Collection
    {
        return $this->organization;
    }

    public function addOrganization(Organization $organization): self
    {
        if (!$this->organization->contains($organization)) {
            $this->organization[] = $organization;
            $organization->setContactDetail($this);
        }

        return $this;
    }

    public function removeOrganization(Organization $organization): self
    {
        if ($this->organization->removeElement($organization)) {
            // set the owning side to null (unless already changed)
            if ($organization->getContactDetail() === $this) {
                $organization->setContactDetail(null);
            }
        }

        return $this;
    }

    public function getModifiedAt(): ?\DateTimeImmutable
    {
        return $this->modified_at;
    }

    public function setModifiedAt(\DateTimeImmutable $modified_at): self
    {
        $this->modified_at = $modified_at;

        return $this;
    }
}

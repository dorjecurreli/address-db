<?php

namespace App\Entity;

use App\Repository\OrganizationOrganizationTypeRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=OrganizationOrganizationTypeRepository::class)
 */
class OrganizationOrganizationType
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     *
     * @Assert\Valid()
     * @ORM\ManyToOne(targetEntity=Organization::class, inversedBy="organizationOrganizationType")
     * @JoinColumn(name="organization_id", referencedColumnName="id")
     */
    private $organization;


    /**
     *
     * @Assert\Valid()
     * @ORM\ManyToOne(targetEntity=OrganizationType::class, inversedBy="organizationOrganizationType")
     * @JoinColumn(name="organization_type_id", referencedColumnName="id")
     */
    private $organizationType;




    public function getId(): ?int
    {
        return $this->id;
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

    public function getOrganizationType(): ?OrganizationType
    {
        return $this->organizationType;
    }

    public function setOrganizationType(?OrganizationType $organizationType): self
    {
        $this->organizationType = $organizationType;

        return $this;
    }




}

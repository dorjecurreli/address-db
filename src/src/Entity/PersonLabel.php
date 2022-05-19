<?php

namespace App\Entity;

use App\Repository\PersonLabelRepository;
use Doctrine\ORM\Mapping as ORM;


use Doctrine\ORM\Mapping\JoinColumn;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=PersonLabelRepository::class)
 */
class PersonLabel
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotNull()
     * @ORM\Column(type="boolean")
     */
    private $isBlacklisted;

    /**
     * @Assert\NotNull()
     * @ORM\Column(type="boolean")
     */
    private $isVIP;

    /**
     * @Assert\NotNull()
     * @ORM\Column(type="boolean")
     */
    private $isContactable;

    /**
     *
     * @Assert\Valid()
     * @ORM\ManyToOne(targetEntity=Person::class, inversedBy="personLabel")
     * @JoinColumn(name="person_id", referencedColumnName="id")
     */
    private $person;

    /**
     * @Assert\Valid()
     * @ORM\ManyToOne(targetEntity=Label::class, inversedBy="personLabel")
     * @JoinColumn(name="label_id", referencedColumnName="id")
     */
    private $label;



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIsBlacklisted(): ?bool
    {
        return $this->isBlacklisted;
    }

    public function setIsBlacklisted(bool $isBlacklisted): self
    {
        $this->isBlacklisted = $isBlacklisted;

        return $this;
    }

    public function getIsVIP(): ?bool
    {
        return $this->isVIP;
    }

    public function setIsVIP(bool $isVIP): self
    {
        $this->isVIP = $isVIP;

        return $this;
    }

    public function getIsContactable(): ?bool
    {
        return $this->isContactable;
    }

    public function setIsContactable(bool $isContactable): self
    {
        $this->isContactable = $isContactable;

        return $this;
    }

    public function getPerson(): ?Person
    {
        return $this->person;
    }

    public function setPerson(?Person $person): self
    {
        $this->person = $person;

        return $this;
    }

    public function getLabel(): ?Label
    {
        return $this->label;
    }

    public function setLabel(?Label $label): self
    {
        $this->label = $label;

        return $this;
    }



}

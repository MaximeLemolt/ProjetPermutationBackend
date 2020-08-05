<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AnnonceRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Annonce
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"ads_get", "users_get"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=128, nullable=true)
     * @Assert\Length(min = 0, max = 128)
     * @Groups({"ads_get", "users_get"})
     */
    private $informations;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\User", inversedBy="annonce")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank
     * @Assert\NotNull
     * @Groups("ads_get")
     */
    private $user;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"ads_get", "users_get"})
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({"ads_get", "users_get"})
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="smallint", options={"default":1})
     * @Groups({"ads_get", "users_get"})
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Grade", inversedBy="annonces")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank
     * @Groups({"ads_get", "users_get"})
     */
    private $grade;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Service", inversedBy="annonces")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank
     * @Groups({"ads_get", "users_get"})
     */
    private $service;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\City", inversedBy="annonces")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank
     * @Groups({"ads_get", "users_get"})
     */
    private $city;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\City", inversedBy="annoncesMutation")
     * @Groups({"ads_get", "users_get"})
     */
    private $mutationCity;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Department", inversedBy="annonces")
     * @Groups({"ads_get", "users_get"})
     */
    private $mutationDepartment;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Region", inversedBy="annonces")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"ads_get", "users_get"})
     * @Assert\NotBlank
     */
    private $mutationRegion;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->status = 1;
    }
    public function __toString()
    {
        return $this->informations;
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getInformations(): ?string
    {
        return $this->informations;
    }

    public function setInformations(?string $informations): self
    {
        $this->informations = $informations;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function setCreatedAt($createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt($updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getGrade(): ?Grade
    {
        return $this->grade;
    }

    public function setGrade(?Grade $grade): self
    {
        $this->grade = $grade;

        return $this;
    }

    public function getService(): ?Service
    {
        return $this->service;
    }

    public function setService(?Service $service): self
    {
        $this->service = $service;

        return $this;
    }

    public function getCity(): ?City
    {
        return $this->city;
    }

    public function setCity(?City $city): self
    {
        $this->city = $city;

        return $this;
    }

    /**
     * @ORM\PreUpdate
     */
    public function setUpdatedAtValue()
    {
        $this->updatedAt = new \DateTime();
    }

    public function getMutationCity(): ?City
    {
        return $this->mutationCity;
    }

    public function setMutationCity(?City $mutationCity): self
    {
        $this->mutationCity = $mutationCity;

        return $this;
    }

    public function getMutationDepartment(): ?Department
    {
        return $this->mutationDepartment;
    }

    public function setMutationDepartment(?Department $mutationDepartment): self
    {
        $this->mutationDepartment = $mutationDepartment;

        return $this;
    }

    public function getMutationRegion(): ?Region
    {
        return $this->mutationRegion;
    }

    public function setMutationRegion(?Region $mutationRegion): self
    {
        $this->mutationRegion = $mutationRegion;

        return $this;
    }
}

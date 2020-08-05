<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DepartmentRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Department
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"departments_get", "ads_get"})
     */
    private $id;

    /**
     * @ORM\Column(type="smallint")
     * @Groups("departments_get")
     */
    private $code;

    /**
     * @ORM\Column(type="string", length=100)
     * @Groups({"ads_get", "departments_get", "users_get"})
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Region")
     * @ORM\JoinColumn(nullable=false)
     */
    private $region;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Annonce", mappedBy="mutationDepartment")
     */
    private $annonces;

    public function __construct()
    {
        $this->annonces = new ArrayCollection();
    }
    public function __toString()
    {
        return $this->name;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?int
    {
        return $this->code;
    }

    public function setCode(int $code): self
    {
        $this->code = $code;

        return $this;
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

    public function getRegion(): ?Region
    {
        return $this->region;
    }

    public function setRegion(?Region $region): self
    {
        $this->region = $region;

        return $this;
    }

    /**
     * @return Collection|Annonce[]
     */
    public function getAnnonces(): Collection
    {
        return $this->annonces;
    }

    public function addAnnonce(Annonce $annonce): self
    {
        if (!$this->annonces->contains($annonce)) {
            $this->annonces[] = $annonce;
            $annonce->setMutationDepartment($this);
        }

        return $this;
    }

    public function removeAnnonce(Annonce $annonce): self
    {
        if ($this->annonces->contains($annonce)) {
            $this->annonces->removeElement($annonce);
            // set the owning side to null (unless already changed)
            if ($annonce->getMutationDepartment() === $this) {
                $annonce->setMutationDepartment(null);
            }
        }

        return $this;
    }

    /**
     * @ORM\PreUpdate
     */
    public function setUpdatedAtValue()
    {
        $this->updatedAt = new \DateTime();
    }
}

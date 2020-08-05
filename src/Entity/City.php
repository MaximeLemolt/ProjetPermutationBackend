<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CityRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class City
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups("ads_get")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     * @Groups({"ads_get", "users_get"})
     */
    private $name;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Annonce", mappedBy="city", orphanRemoval=true)
     */
    private $annonces;

    /**
     * @ORM\Column(type="integer")
     */
    private $codeINSEE;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Department")
     * @ORM\JoinColumn(nullable=false)
     */
    private $department;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Annonce", mappedBy="mutationCity")
     */
    private $annoncesMutation;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Groups("ads_get")
     */
    private $latitude;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Groups("ads_get")
     */
    private $longitude;

    public function __construct()
    {
        $this->annonces = new ArrayCollection();
        $this->createdAt = new \DateTime();
        $this->annoncesMutation = new ArrayCollection();
    }
    public function __toString()
    {
        return $this->name;
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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

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
            $annonce->setCity($this);
        }

        return $this;
    }

    public function removeAnnonce(Annonce $annonce): self
    {
        if ($this->annonces->contains($annonce)) {
            $this->annonces->removeElement($annonce);
            // set the owning side to null (unless already changed)
            if ($annonce->getCity() === $this) {
                $annonce->setCity(null);
            }
        }

        return $this;
    }

    public function getCodeINSEE(): ?int
    {
        return $this->codeINSEE;
    }

    public function setCodeINSEE(int $codeINSEE): self
    {
        $this->codeINSEE = $codeINSEE;

        return $this;
    }

    public function getDepartment(): ?Department
    {
        return $this->department;
    }

    public function setDepartment(?Department $department): self
    {
        $this->department = $department;

        return $this;
    }

    /**
     * @return Collection|Annonce[]
     */
    public function getAnnoncesMutation(): Collection
    {
        return $this->annoncesMutation;
    }

    public function addAnnoncesMutation(Annonce $annoncesMutation): self
    {
        if (!$this->annoncesMutation->contains($annoncesMutation)) {
            $this->annoncesMutation[] = $annoncesMutation;
            $annoncesMutation->setMutationCity($this);
        }

        return $this;
    }

    public function removeAnnoncesMutation(Annonce $annoncesMutation): self
    {
        if ($this->annoncesMutation->contains($annoncesMutation)) {
            $this->annoncesMutation->removeElement($annoncesMutation);
            // set the owning side to null (unless already changed)
            if ($annoncesMutation->getMutationCity() === $this) {
                $annoncesMutation->setMutationCity(null);
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

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(?float $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(?float $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }
}

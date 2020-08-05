<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity("email")
 * @UniqueEntity("pseudo")
 * @ORM\HasLifecycleCallbacks()
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"users_get", "ads_get", "messages_get"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\NotBlank
     * @Assert\Email
     * @Groups("users_get")
     */
    private $email;

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     *  @Assert\Regex(
     *  pattern="/^(?=.{8,}$)(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]).*$/",
     *  message="Votre mot de passe doit contenir au moins 1 chiffre, 1 majuscule, 1 minuscule et avoir une longueur d'au moins 8 caractères."
     * )
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=50)
     * @Groups({"ads_get", "users_get", "messages_get"})
     * @Assert\NotBlank
     */
    private $pseudo;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank
     * @Groups("users_get")
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank
     * @Groups("users_get")
     */
    private $lastname;

    /**
     * @ORM\Column(type="smallint", options={"default":1})
     */
    private $status;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Role")
     */
    private $userRoles;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Annonce", mappedBy="user", cascade={"persist", "remove"})
     * @Groups("users_get")
     */
    private $annonce;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Message", mappedBy="author", orphanRemoval=true)
     */
    private $sendedMessage;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Message", mappedBy="recipient", orphanRemoval=true)
     */
    private $receivedMessage;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $resetToken;

    public function __construct()
    {
        $this->userRoles = new ArrayCollection();
        $this->createdAt = new \DateTime();
        $this->status = 1;
        $this->sendedMessage = new ArrayCollection();
        $this->receivedMessage = new ArrayCollection();
    }
    public function __toString()
    {
        return $this->email;
    }

    public function getId(): ?int
    {
        return $this->id;
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
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * Attention, la méthode getRoles() est spécifique.
     * Elle est utilisée lorque mon utilisateur se connecte,
     * pour que Symfony sache quels rôles sont associés à mon user en cours de connexion pour déclencher les ACL
     * 
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->userRoles;

        $userRoles = [];

        foreach ($roles as $role) {
            $userRoles[] = $role->getName();
        }

        return $userRoles;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): self
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

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
     * @return Collection|Role[]
     */
    public function getUserRoles(): Collection
    {
        return $this->userRoles;
    }

    public function addUserRole(Role $userRole): self
    {
        if (!$this->userRoles->contains($userRole)) {
            $this->userRoles[] = $userRole;
        }

        return $this;
    }

    public function removeUserRole(Role $userRole): self
    {
        if ($this->userRoles->contains($userRole)) {
            $this->userRoles->removeElement($userRole);
        }

        return $this;
    }

    public function getAnnonce(): ?Annonce
    {
        return $this->annonce;
    }

    public function setAnnonce(?Annonce $annonce): self
    {
        $this->annonce = $annonce;

        // set (or unset) the owning side of the relation if necessary
        if ($annonce->getUser() === null) {
            $annonce->setUser($this);
        }

        return $this;
    }

    /**
     * @return Collection|Message[]
     */
    public function getSendedMessage(): Collection
    {
        return $this->sendedMessage;
    }

    public function addSendedMessage(Message $sendedMessage): self
    {
        if (!$this->sendedMessage->contains($sendedMessage)) {
            $this->sendedMessage[] = $sendedMessage;
            $sendedMessage->setAuthor($this);
        }

        return $this;
    }

    public function removeSendedMessage(Message $sendedMessage): self
    {
        if ($this->sendedMessage->contains($sendedMessage)) {
            $this->sendedMessage->removeElement($sendedMessage);
            // set the owning side to null (unless already changed)
            if ($sendedMessage->getAuthor() === $this) {
                $sendedMessage->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Message[]
     */
    public function getReceivedMessage(): Collection
    {
        return $this->receivedMessage;
    }

    public function addReceivedMessage(Message $receivedMessage): self
    {
        if (!$this->receivedMessage->contains($receivedMessage)) {
            $this->receivedMessage[] = $receivedMessage;
            $receivedMessage->setRecipient($this);
        }

        return $this;
    }

    public function removeReceivedMessage(Message $receivedMessage): self
    {
        if ($this->receivedMessage->contains($receivedMessage)) {
            $this->receivedMessage->removeElement($receivedMessage);
            // set the owning side to null (unless already changed)
            if ($receivedMessage->getRecipient() === $this) {
                $receivedMessage->setRecipient(null);
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

    public function getResetToken(): ?string
    {
        return $this->resetToken;
    }

    public function setResetToken(?string $resetToken): self
    {
        $this->resetToken = $resetToken;

        return $this;
    }
}

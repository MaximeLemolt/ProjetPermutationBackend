<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TokenBlacklistRepository")
 */
class TokenBlacklist
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $token;

    /**
     * @ORM\Column(type="datetime")
     */
    private $expiration;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(string $token): self
    {
        $this->token = $token;

        return $this;
    }

    public function getExpiration(): ?\DateTimeInterface
    {
        return $this->expiration;
    }

    public function setExpiration(\DateTimeInterface $expiration): self
    {
        $this->expiration = $expiration;

        return $this;
    }
}

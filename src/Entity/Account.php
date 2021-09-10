<?php

namespace App\Entity;

use App\Repository\AccountRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass=AccountRepository::class)
 */
class Account
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity=User::class, inversedBy="account", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="decimal", precision=21, scale=5, options={"default":0})
     */
    private $usd;

    /**
     * @ORM\Column(type="decimal", precision=22, scale=11, options={"default":0})
     */
    private $btc;

    /**
     * @ORM\Column(type="decimal", precision=32, scale=21, options={"default":0})
     */
    private $eth;

    /**
     * @ORM\Column(type="datetime_immutable")
     * @Gedmo\Timestampable(on="create")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime_immutable")
     * @Gedmo\Timestampable(on="update")
     */
    private $updatedAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getUsd(): ?float
    {
        return $this->usd;
    }

    public function setUsd(float $usd): self
    {
        $this->usd = $usd;

        return $this;
    }

    public function getBtc(): ?float
    {
        return $this->btc;
    }

    public function setBtc(float $btc): self
    {
        $this->btc = $btc;

        return $this;
    }

    public function getEth(): ?float
    {
        return $this->eth;
    }

    public function setEth(float $eth): self
    {
        $this->eth = $eth;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}

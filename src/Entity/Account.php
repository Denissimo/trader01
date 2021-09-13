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
    public const SCALE_USD = 2;
    public const SCALE_BTC = 8;
    public const SCALE_ETH = 18;

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
    private $amountUsd;

    /**
     * @ORM\Column(type="decimal", precision=22, scale=11, options={"default":0})
     */
    private $amountBtc;

    /**
     * @ORM\Column(type="decimal", precision=32, scale=21, options={"default":0})
     */
    private $amountEth;

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

    public function getAmountUsd(): ?float
    {
        return $this->amountUsd;
    }

    public function setAmountUsd(float $amountUsd): self
    {
        $this->amountUsd = $amountUsd;

        return $this;
    }

    public function getAmountBtc(): ?float
    {
        return $this->amountBtc;
    }

    public function setAmountBtc(float $amountBtc): self
    {
        $this->amountBtc = $amountBtc;

        return $this;
    }

    public function getAmountEth(): ?float
    {
        return $this->amountEth;
    }

    public function setAmountEth(float $amountEth): self
    {
        $this->amountEth = $amountEth;

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

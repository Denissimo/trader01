<?php

namespace App\Entity;

use App\Repository\AccuralRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass=AccuralRepository::class)
 */
class Accural
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="accurals")
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
     * @ORM\ManyToOne(targetEntity=User::class)
     */
    private $sourceUser;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $level;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $comment;

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

    public function setUser(?User $user): self
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

    public function getSourceUser(): ?User
    {
        return $this->sourceUser;
    }

    public function setSourceUser(?User $sourceUser): self
    {
        $this->sourceUser = $sourceUser;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

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

    public function getLevel(): ?int
    {
        return $this->level;
    }

    public function setLevel(?int $level): self
    {
        $this->level = $level;

        return $this;
    }
}

<?php

namespace App\Entity;

use App\Repository\UserTreeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UserTreeRepository::class)
 */
class UserTree
{
    public const PARENT_LEVEL_MAX = 10;

    public static $levels = [
        1 => 0.15,
        2 => 0.10,
        3 => 0.08,
        4 => 0.06,
        5 => 0.04,
        6 => 0.03,
        7 => 0.02,
        8 => 0.01,
        9 => 0.005,
        10 => 0.005
    ];

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="childUsers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $childUser;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="parentUsers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $parentUser;

    /**
     * @ORM\Column(type="integer")
     */
    private $level;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getChildUser(): ?User
    {
        return $this->childUser;
    }

    public function setChildUser(?User $childUser): self
    {
        $this->childUser = $childUser;

        return $this;
    }

    public function getParentUser(): ?User
    {
        return $this->parentUser;
    }

    public function setParentUser(?User $parentUser): self
    {
        $this->parentUser = $parentUser;

        return $this;
    }

    public function getLevel(): ?int
    {
        return $this->level;
    }

    public function setLevel(int $level): self
    {
        $this->level = $level;

        return $this;
    }
}

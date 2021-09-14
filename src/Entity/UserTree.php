<?php

namespace App\Entity;

use App\Repository\UserTreeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UserTreeRepository::class)
 */
class UserTree
{
    const PARENT_LEVEL_MAX = 10;

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

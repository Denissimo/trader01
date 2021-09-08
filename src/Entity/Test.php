<?php

namespace App\Entity;

use App\Repository\TestRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TestRepository::class)
 */
class Test
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $fieldInt;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $fieldString;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFieldInt(): ?int
    {
        return $this->fieldInt;
    }

    public function setFieldInt(int $fieldInt): self
    {
        $this->fieldInt = $fieldInt;

        return $this;
    }

    public function getFieldString(): ?string
    {
        return $this->fieldString;
    }

    public function setFieldString(?string $fieldString): self
    {
        $this->fieldString = $fieldString;

        return $this;
    }
}

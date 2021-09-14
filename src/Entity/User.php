<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 * @UniqueEntity(fields={"username"}, message="There is already an account with this username")
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    public const INVITE = 'invitation_code';

    public const DAFAULT_PASSWORD = '123456';

    public const LEVEL_MAX = 10;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $username;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="children")
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="parent")
     */
    private $children;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $hash;

    /**
     * @ORM\OneToMany(targetEntity=Purse::class, mappedBy="user", orphanRemoval=true)
     */
    private $purses;

    /**
     * @ORM\Column(type="datetime_immutable", options={"default": "CURRENT_TIMESTAMP"})
     *  @Gedmo\Timestampable(on="create")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime_immutable", options={"default": "CURRENT_TIMESTAMP"})
     *  @Gedmo\Timestampable(on="update")
     */
    private $updatedAt;

    /**
     * @ORM\OneToOne(targetEntity=Account::class, mappedBy="user", cascade={"persist", "remove"})
     */
    private $account;

    /**
     * @ORM\OneToMany(targetEntity=Deal::class, mappedBy="user", orphanRemoval=true)
     */
    private $deals;

    /**
     * @ORM\OneToMany(targetEntity=UserTree::class, mappedBy="childUser", orphanRemoval=true)
     */
    private $childUsers;

    /**
     * @ORM\OneToMany(targetEntity=UserTree::class, mappedBy="parentUser", orphanRemoval=true)
     */
    private $parentUsers;

    /**
     * @ORM\OneToMany(targetEntity=Accurals::class, mappedBy="user", orphanRemoval=true)
     */
    private $accurals;

    public function __construct()
    {
        $this->children = new ArrayCollection();
        $this->purses = new ArrayCollection();
        $this->deals = new ArrayCollection();
        $this->childUsers = new ArrayCollection();
        $this->parentUsers = new ArrayCollection();
        $this->accurals = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;
        $this->generateHash();

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function setParent(?self $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getChildren(): Collection
    {
        return $this->children;
    }

    public function addChild(self $child): self
    {
        if (!$this->children->contains($child)) {
            $this->children[] = $child;
            $child->setParent($this);
        }

        return $this;
    }

    public function removeChild(self $child): self
    {
        if ($this->children->removeElement($child)) {
            // set the owning side to null (unless already changed)
            if ($child->getParent() === $this) {
                $child->setParent(null);
            }
        }

        return $this;
    }

    public function getHash(): ?string
    {
        return $this->hash;
    }

    public function setHash(?string $hash): self
    {
        $this->hash = $hash;

        return $this;
    }

    /**
     * @return Collection|Purse[]
     */
    public function getPurses(): Collection
    {
        return $this->purses;
    }

    public function addPurse(Purse $purse): self
    {
        if (!$this->purses->contains($purse)) {
            $this->purses[] = $purse;
            $purse->setUser($this);
        }

        return $this;
    }

    public function removePurse(Purse $purse): self
    {
        if ($this->purses->removeElement($purse)) {
            // set the owning side to null (unless already changed)
            if ($purse->getUser() === $this) {
                $purse->setUser(null);
            }
        }

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

    public function getAccount(): ?Account
    {
        return $this->account;
    }

    public function setAccount(Account $account): self
    {
        // set the owning side of the relation if necessary
        if ($account->getUser() !== $this) {
            $account->setUser($this);
        }

        $this->account = $account;

        return $this;
    }

    private function generateHash()
    {
        $this->hash = hash("crc32", (string) $this->username);
    }

    /**
     * @return Collection|Deal[]
     */
    public function getDeals(): Collection
    {
        return $this->deals;
    }

    public function addDeal(Deal $deal): self
    {
        if (!$this->deals->contains($deal)) {
            $this->deals[] = $deal;
            $deal->setUser($this);
        }

        return $this;
    }

    public function removeDeal(Deal $deal): self
    {
        if ($this->deals->removeElement($deal)) {
            // set the owning side to null (unless already changed)
            if ($deal->getUser() === $this) {
                $deal->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|UserTree[]
     */
    public function getChildUsers(): Collection
    {
        return $this->childUsers;
    }

    public function addChildUser(UserTree $childUser): self
    {
        if (!$this->childUsers->contains($childUser)) {
            $this->childUsers[] = $childUser;
            $childUser->setChildUser($this);
        }

        return $this;
    }

    public function removeChildUser(UserTree $childUser): self
    {
        if ($this->childUsers->removeElement($childUser)) {
            // set the owning side to null (unless already changed)
            if ($childUser->getChildUser() === $this) {
                $childUser->setChildUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|UserTree[]
     */
    public function getParentUsers(): Collection
    {
        return $this->parentUsers;
    }

    public function addParentUser(UserTree $parentUser): self
    {
        if (!$this->parentUsers->contains($parentUser)) {
            $this->parentUsers[] = $parentUser;
            $parentUser->setParentUser($this);
        }

        return $this;
    }

    public function removeParentUser(UserTree $parentUser): self
    {
        if ($this->parentUsers->removeElement($parentUser)) {
            // set the owning side to null (unless already changed)
            if ($parentUser->getParentUser() === $this) {
                $parentUser->setParentUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Accurals[]
     */
    public function getAccurals(): Collection
    {
        return $this->accurals;
    }

    public function addAccural(Accurals $accural): self
    {
        if (!$this->accurals->contains($accural)) {
            $this->accurals[] = $accural;
            $accural->setUser($this);
        }

        return $this;
    }

    public function removeAccural(Accurals $accural): self
    {
        if ($this->accurals->removeElement($accural)) {
            // set the owning side to null (unless already changed)
            if ($accural->getUser() === $this) {
                $accural->setUser(null);
            }
        }

        return $this;
    }
}

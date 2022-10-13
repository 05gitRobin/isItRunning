<?php

namespace firesnake\isItRunning\entities;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="users")
 */
class User
{
    /**
     * @ORM\Id()
     * @ORM\Column()
     * @ORM\GeneratedValue()
     */
    private int $id;

    /**
     * @ORM\Column(type="string", unique=true)
     * @var string
     */
    private string $username;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    private string $password;

    /**
     * @ORM\OneToMany(targetEntity="CheckableEnvironment", mappedBy="owner")
     * @var Collection
     */
    private Collection $environments;

    /**
     * @ORM\OneToMany(targetEntity="Check", mappedBy="owner")
     * @var Collection
     */
    private Collection $checks;

    public function __construct()
    {
        $this->environments = new ArrayCollection();
        $this->checks = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    /**
     * @param string $password
     */
    public final function setPassword(string $password): void
    {
        $this->password = password_hash($password, PASSWORD_BCRYPT);
    }

    public final function passwordMatches(string $password): bool
    {
        if(password_needs_rehash($this->password, PASSWORD_BCRYPT)) {
            $this->setPassword($password);
        }

        return password_verify($password, $this->password);
    }

    public function getEnvironments(): Collection
    {
        return $this->environments;
    }

    public function getChecks(): Collection
    {
        return $this->checks;
    }
}
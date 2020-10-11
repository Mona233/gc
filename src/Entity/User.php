<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Swagger\Annotations as SWG;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="users", uniqueConstraints={
 *     @ORM\UniqueConstraint(name="email_UNIQUE", columns={"email"}),
 * })
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @Groups({"user:get", "user:index", "user:id"})
     */
    private int $id;

    /**
     * @ORM\Column(name="name", type="string", length=100, nullable=false)
     * @Groups({"user:create", "user:get", "user:update", "user:index"})
     * @SWG\Property(property="name")
     *
     * @Assert\NotBlank(message="name_cannot_be_blank")
     * @Assert\Type("string")
     * @Assert\Length(max=100, min=2, maxMessage="name_must_be_shorter_than_100_characters", minMessage="name_must_be_minimum_2_characters_long")
     */
    private string $name;

    /**
     * @ORM\Column(name="username", type="string", length=255, nullable=false)
     * @Groups({"user:create", "user:get", "user:update", "user:index"})
     * @SWG\Property(property="username")
     *
     * @Assert\Type("string")
     */
    private string $username;

    /**
     * @ORM\Column(name="email", type="string", length=100, nullable=false)
     * @Groups({"user:create", "user:update", "user:get", "user:index"})
     *
     * @Assert\NotBlank(message="email_cannot_be_blank")
     * @Assert\Email(message="must_be_a_valid_email_address")
     */
    private string $email;

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getRoles()
    {
    }

    public function getPassword()
    {
    }

    public function getSalt()
    {
    }

    public function eraseCredentials()
    {
    }
}

<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Swagger\Annotations as SWG;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="posts")
 * @ORM\Entity(repositoryClass="App\Repository\PostRepository")
 */
class Post
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @Groups({"post:get", "post:index", "post:id"})
     */
    private int $id;

    /**
     * @ORM\Column(name="title", type="string", length=100, nullable=false)
     * @Groups({"post:create", "post:get", "post:update", "post:index"})
     * @SWG\Property(property="title")
     *
     * @Assert\NotBlank(message="title_cannot_be_blank")
     * @Assert\Type("string")
     */
    private string $title;

    /**
     * @ORM\Column(name="body", type="string", length=255, nullable=false)
     * @Groups({"post:create", "post:get", "post:update", "post:index"})
     * @SWG\Property(property="body")
     *
     * @Assert\Type("string")
     */
    private string $body;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * @Groups({"post:user"})
     */
    private User $user;

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function setBody(string $body): self
    {
        $this->body = $body;

        return $this;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }
}

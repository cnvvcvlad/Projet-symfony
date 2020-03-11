<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;


/**
 * @ORM\Entity(repositoryClass="App\Repository\CommentsRepository")
 */
class Comments
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups("article:read")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     * @Groups("article:read")
     */
    private $com_content;

    /**
     * @ORM\Column(type="boolean")
     * @Groups("article:read")
     */
    private $actif = false;


    /**
     * @ORM\Column(type="boolean")
     * @Groups("article:read")
     */
    private $rgpd = false;

    /**
     * @ORM\Column(type="string", length=30)
     * @Groups("article:read")
     */
    private $email_author;


    /**
     * @ORM\Column(type="datetime")
     * @Groups("article:read")
     */
    private $com_created_at;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Articles", inversedBy="comments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $article;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Users", inversedBy="comments")
     * @ORM\JoinColumn(nullable=false)
     * @Groups("article:read")
     */
    private $user;

    public function getId(): int
    {
        return $this->id;
    }

    public function getComContent(): ?string
    {
        return $this->com_content;
    }

    public function setComContent(string $com_content): self
    {
        $this->com_content = $com_content;

        return $this;
    }

    public function getActif(): bool
    {
        return $this->actif;
    }

    public function setActif(bool $actif): self
    {
        $this->actif = $actif;

        return $this;
    }


    public function getRgpd(): bool
    {
        return $this->rgpd;
    }

    public function setRgpd(bool $rgpd): self
    {
        $this->rgpd = $rgpd;

        return $this;
    }

    public function getEmailAuthor(): ?string
    {
        return $this->email_author;
    }

    public function setEmailAuthor(string $email_author): self
    {
        $this->email_author = $email_author;

        return $this;
    }


    public function getComCreatedAt(): \DateTimeInterface
    {
        return $this->com_created_at;
    }

    public function setComCreatedAt(\DateTimeInterface $com_created_at): self
    {
        $this->com_created_at = $com_created_at;

        return $this;
    }

    public function getArticle(): Articles
    {
        return $this->article;
    }

    public function setArticle(Articles $article): self
    {
        $this->article = $article;

        return $this;
    }

    public function getUser(): ?Users
    {
        return $this->user;
    }

    public function setUser(Users $user): self
    {
        $this->user = $user;

        return $this;
    }
}

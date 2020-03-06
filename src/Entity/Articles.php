<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ArticlesRepository")
 */
class Articles
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $art_title;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $art_slug;

    /**
     * @ORM\Column(type="text")
     */
    private $art_description;

    /**
     * @ORM\Column(type="text")
     */
    private $art_content;

    /**
     * @ORM\Column(type="string", length=40)
     */
    private $art_image;

    /**
     * @ORM\Column(type="datetime")
     */
    private $art_created_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $art_updated_at;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Users", inversedBy="articles")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comments", mappedBy="article", orphanRemoval=true)
     */
    private $comments;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\MotsCles", inversedBy="articles")
     */
    private $mots_cles;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Categories", inversedBy="articles")
     */
    private $categories;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->mots_cles = new ArrayCollection();
        $this->categories = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getArtTitle(): ?string
    {
        return $this->art_title;
    }

    public function setArtTitle(?string $art_title): self
    {
        $this->art_title = $art_title;

        return $this;
    }

    public function getArtSlug(): ?string
    {
        return $this->art_slug;
    }

    public function setArtSlug(?string $art_slug): self
    {
        $this->art_slug = $art_slug;

        return $this;
    }

    public function getArtDescription(): ?string
    {
        return $this->art_description;
    }

    public function setArtDescription(?string $art_description): self
    {
        $this->art_description = $art_description;

        return $this;
    }

    public function getArtContent(): ?string
    {
        return $this->art_content;
    }

    public function setArtContent(?string $art_content): self
    {
        $this->art_content = $art_content;

        return $this;
    }

    public function getArtImage(): ?string
    {
        return $this->art_image;
    }

    public function setArtImage(?string $art_image): self
    {
        $this->art_image = $art_image;

        return $this;
    }

    public function getArtCreatedAt(): \DateTimeInterface
    {
        return $this->art_created_at;
    }

    public function setArtCreatedAt(\DateTimeInterface $art_created_at): self
    {
        $this->art_created_at = $art_created_at;

        return $this;
    }

    public function getArtUpdatedAt(): \DateTimeInterface
    {
        return $this->art_updated_at;
    }

    public function setArtUpdatedAt( \DateTimeInterface $art_updated_at): self
    {
        $this->art_updated_at = $art_updated_at;

        return $this;
    }

    public function getUser(): Users
    {
        return $this->user;
    }

    public function setUser(Users $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection|Comments[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comments $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setArticle($this);
        }

        return $this;
    }

    public function removeComment(Comments $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getArticle() === $this) {
                $comment->setArticle(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|MotsCles[]
     */
    public function getMotsCles(): ?Collection
    {
        return $this->mots_cles;
    }

    public function addMotsCle(?MotsCles $motsCle): self
    {
        if (!$this->mots_cles->contains($motsCle)) {
            $this->mots_cles[] = $motsCle;
        }

        return $this;
    }

    public function removeMotsCle(?MotsCles $motsCle): self
    {
        if ($this->mots_cles->contains($motsCle)) {
            $this->mots_cles->removeElement($motsCle);
        }

        return $this;
    }

    /**
     * @return Collection|Categories[]
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Categories $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
        }

        return $this;
    }

    public function removeCategory(Categories $category): self
    {
        if ($this->categories->contains($category)) {
            $this->categories->removeElement($category);
        }

        return $this;
    }
}

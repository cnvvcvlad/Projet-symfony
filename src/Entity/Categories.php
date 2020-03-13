<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CategoriesRepository")
 */
class Categories
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $cat_title;

    /**
     * @ORM\Column(type="text")
     */
    private $cat_description;

    /**
     * @ORM\Column(type="string", length=40)
     */
    private $cat_slug;

    /**
     * @ORM\Column(type="string", length=40)
     */
    private $cat_image;

    /**
     * @ORM\Column(type="datetime")
     */
    private $cat_created_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $cat_updated_at;


    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Articles", mappedBy="categories")
     */
    private $articles;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Users", inversedBy="categories")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;



    public function __construct()
    {
        $this->articles = new ArrayCollection();
    }

    //on utilise la méthode magique pour convertir les données en string
    public function __toString()
    {
        return $this->cat_title;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getCatTitle(): string
    {
        return $this->cat_title;
    }


    public function setCatTitle(string $cat_title): self
    {
        $this->cat_title = $cat_title;

        return $this;
    }

    public function getCatDescription(): string
    {
        return $this->cat_description;
    }

    public function setCatDescription(string $cat_description): self
    {
        $this->cat_description = $cat_description;

        return $this;
    }

    public function getCatSlug(): string
    {
        return $this->cat_slug;
    }

    public function setCatSlug(string $cat_slug): self
    {
        $this->cat_slug = $cat_slug;

        return $this;
    }

    public function getCatImage(): string
    {
        return $this->cat_image;
    }

    public function setCatImage(string $cat_image): self
    {
        $this->cat_image = $cat_image;

        return $this;
    }

    public function getCatCreatedAt(): \DateTimeInterface
    {
        return $this->cat_created_at;
    }

    public function setCatCreatedAt(\DateTimeInterface $cat_created_at): self
    {
        $this->cat_created_at = $cat_created_at;

        return $this;
    }

    public function getCatUpdatedAt(): \DateTimeInterface
    {
        return $this->cat_updated_at;
    }

    public function setCatUpdatedAt(\DateTimeInterface $cat_updated_at): self
    {
        $this->cat_updated_at = $cat_updated_at;

        return $this;
    }


    /**
     * @return Collection|Articles[]
     */
    public function getArticles(): Collection
    {
        return $this->articles;
    }

    public function addArticle(Articles $article): self
    {
        if (!$this->articles->contains($article)) {
            $this->articles[] = $article;
            $article->addCategory($this);
        }

        return $this;
    }

    public function removeArticle(Articles $article): self
    {
        if ($this->articles->contains($article)) {
            $this->articles->removeElement($article);
            $article->removeCategory($this);
        }

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

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(?string $category): self
    {
        $this->category = $category;

        return $this;
    }
}

<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MotsClesRepository")
 */
class MotsCles
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
    private $mot_cle;

    /**
     * @ORM\Column(type="string", length=40)
     */
    private $mot_cle_slug;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Articles", mappedBy="mots_cles")
     */
    private $articles;

    public function __construct()
    {
        $this->articles = new ArrayCollection();
    }

    //on utilise la méthode magique pour convertir les données en string
    public  function __toString()
    {
        return $this->mot_cle;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getMotCle(): string
    {
        return $this->mot_cle;
    }

    public function setMotCle(string $mot_cle): self

    {
        $this->mot_cle = $mot_cle;

        return $this;
    }

    public function getMotCleSlug(): string
    {
        return $this->mot_cle_slug;
    }

    public function setMotCleSlug(string $mot_cle_slug): self
    {
        $this->mot_cle_slug = $mot_cle_slug;

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
            $article->addMotsCle($this);
        }

        return $this;
    }

    public function removeArticle(Articles $article): self
    {
        if ($this->articles->contains($article)) {
            $this->articles->removeElement($article);
            $article->removeMotsCle($this);
        }

        return $this;
    }
}

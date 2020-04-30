<?php

namespace App\Entity;

use App\Entity\Users;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
// on utilise les containtes de validation
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ArticlesRepository")
 */
class Articles
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups("article:read")
     */

    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("article:read")
     * @Assert\NotBlank(message="Le titre est obligatoire")
     * @Assert\Length(min=3)
     */
    private $art_title;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("article:read")
     */
    private $art_slug;

    /**
     * @ORM\Column(type="text")
     * @Groups("article:read")
     */
    private $art_description;

    /**
     * @ORM\Column(type="text")
     * @Groups("article:read")
     * on va surcharger le message en ecrivant le notre
     * @Assert\NotBlank(message="Le contenu est obligatoire")
     * @Assert\Length(min=3)
     */
    private $art_content;

    /**
     * @ORM\Column(type="string", length=40)
     * @Groups("article:read")
     */
    private $art_image;

    /**
     * @ORM\Column(type="datetime")
     * @Groups("article:read")
     */
    private $art_created_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups("article:read")
     */
    private $art_updated_at;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Users", inversedBy="articles")
     * @ORM\JoinColumn(nullable=false)
     * @Groups("article:read")
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comments", mappedBy="article", orphanRemoval=true)
     * @Groups("article:read")
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

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\PostLike", mappedBy="article")
     */
    private $likes;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->mots_cles = new ArrayCollection();
        $this->categories = new ArrayCollection();
        $this->likes = new ArrayCollection();
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

    public function getArtCreatedAt(): ?\DateTimeInterface
    {
        return $this->art_created_at;
    }

    public function setArtCreatedAt(?\DateTimeInterface $art_created_at): self
    {
        $this->art_created_at = $art_created_at;

        return $this;
    }

    public function getArtUpdatedAt(): ?\DateTimeInterface
    {
        return $this->art_updated_at;
    }

    public function setArtUpdatedAt(?\DateTimeInterface $art_updated_at): self
    {
        $this->art_updated_at = $art_updated_at;

        return $this;
    }

    public function getUser(): ?Users
    {
        return $this->user;
    }

    public function setUser(?Users $user): self
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
    public function getCategories(): ?Collection
    {
        return $this->categories;
    }

    public function addCategory(?Categories $category): self
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

    /**
     * @return Collection|PostLike[]
     */
    public function getLikes(): Collection
    {
        return $this->likes;
    }

    public function addLike(PostLike $like): self
    {
        if (!$this->likes->contains($like)) {
            $this->likes[] = $like;
            $like->setArticle($this);
        }

        return $this;
    }

    public function removeLike(PostLike $like): self
    {
        if ($this->likes->contains($like)) {
            $this->likes->removeElement($like);
            // set the owning side to null (unless already changed)
            if ($like->getArticle() === $this) {
                $like->setArticle(null);
            }
        }

        return $this;
    }

    /**
     * Permet de savoir si cet article est liké par un utilisateur
     *
     * @param Users $user
     * @return boolean
     */
    public function isLikedByUser(Users $user) : bool
    {
        foreach ($this->likes as $like) {
            if ($like->getUser() === $user) {
                return true;
            }
        }
        return false;
    }
}

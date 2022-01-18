<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\Column(type: 'string', length: 255)]
    private $description;

    #[ORM\Column(type: 'integer')]
    private $category_order;

    #[ORM\ManyToMany(targetEntity: Article::class, mappedBy: 'categories')]
    private $articles;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'id_parent')]
    private $parent;

    #[ORM\OneToMany(mappedBy: 'parent', targetEntity: self::class)]
    private $id_parent;

    public function __construct()
    {
        $this->articles = new ArrayCollection();
        $this->id_parent = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCategoryOrder(): ?int
    {
        return $this->category_order;
    }

    public function setCategoryOrder(int $category_order): self
    {
        $this->category_order = $category_order;

        return $this;
    }

    /**
     * @return Collection|Article[]
     */
    public function getArticles(): Collection
    {
        return $this->articles;
    }

    public function addArticle(Article $article): self
    {
        if (!$this->articles->contains($article)) {
            $this->articles[] = $article;
            $article->addCategory($this);
        }

        return $this;
    }

    public function removeArticle(Article $article): self
    {
        if ($this->articles->removeElement($article)) {
            $article->removeCategory($this);
        }

        return $this;
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
    public function getIdParent(): Collection
    {
        return $this->id_parent;
    }

    public function addIdParent(self $idParent): self
    {
        if (!$this->id_parent->contains($idParent)) {
            $this->id_parent[] = $idParent;
            $idParent->setParent($this);
        }

        return $this;
    }

    public function removeIdParent(self $idParent): self
    {
        if ($this->id_parent->removeElement($idParent)) {
            // set the owning side to null (unless already changed)
            if ($idParent->getParent() === $this) {
                $idParent->setParent(null);
            }
        }

        return $this;
    }
}

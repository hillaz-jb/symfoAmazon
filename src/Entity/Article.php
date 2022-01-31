<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ArticleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(
    collectionOperations: [
        'post',
        'get' => [
            'normalization_context' => [
                    'groups' => ['light'],
                ],
            "security" => "is_granted('ROLE_ADMIN')"
        ],
    ],
)]
#[ORM\Entity(repositoryClass: ArticleRepository::class)]
class Article
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['light'])]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['light'])]
    private $name;

    #[ORM\Column(type: 'text')]
    #[Groups(['light'])]
    private $description;

    #[ORM\Column(type: 'integer')]
    #[Groups(['light'])]
    private $price;

    #[ORM\Column(type: 'integer', nullable: true)]
    #[Groups(['light'])]
    private $stock;

    #[ORM\Column(type: 'float')]
    #[Groups(['light'])]
    private $tva;

    #[ORM\ManyToMany(targetEntity: Category::class, inversedBy: 'articles')]
    #[Groups(['light'])]
    private $categories;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups(['light'])]
    private $img;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
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

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(?int $stock): self
    {
        $this->stock = $stock;

        return $this;
    }

    public function getTva(): ?float
    {
        return $this->tva;
    }

    public function setTva(float $tva): self
    {
        $this->tva = $tva;

        return $this;
    }

    /**
     * @return Collection|Category[]
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        $this->categories->removeElement($category);

        return $this;
    }

    public function getImg(): ?string
    {
        return $this->img;
    }

    public function setImg(?string $img): self
    {
        $this->img = $img;

        return $this;
    }
}

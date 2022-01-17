<?php

namespace App\Entity;

use App\Repository\OrderLineRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderLineRepository::class)]
class OrderLine
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToMany(targetEntity: Order::class)]
    private $id_Order;

    #[ORM\ManyToMany(targetEntity: Article::class)]
    private $id_Article;

    #[ORM\Column(type: 'integer')]
    private $quantity;

    public function __construct()
    {
        $this->id_Order = new ArrayCollection();
        $this->id_Article = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|Order[]
     */
    public function getIdOrder(): Collection
    {
        return $this->id_Order;
    }

    public function addIdOrder(Order $idOrder): self
    {
        if (!$this->id_Order->contains($idOrder)) {
            $this->id_Order[] = $idOrder;
        }

        return $this;
    }

    public function removeIdOrder(Order $idOrder): self
    {
        $this->id_Order->removeElement($idOrder);

        return $this;
    }

    /**
     * @return Collection|Article[]
     */
    public function getIdArticle(): Collection
    {
        return $this->id_Article;
    }

    public function addIdArticle(Article $idArticle): self
    {
        if (!$this->id_Article->contains($idArticle)) {
            $this->id_Article[] = $idArticle;
        }

        return $this;
    }

    public function removeIdArticle(Article $idArticle): self
    {
        $this->id_Article->removeElement($idArticle);

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }
}

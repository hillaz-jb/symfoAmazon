<?php

namespace App\Entity;

use App\Repository\OrderDeliveryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderDeliveryRepository::class)]
class OrderDelivery
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: Order::class)]
    #[ORM\JoinColumn(nullable: false)]
    private $id_Order;

    #[ORM\OneToMany(mappedBy: 'orderDelivery', targetEntity: Address::class)]
    private $id_Adress;

    #[ORM\Column(type: 'string', length: 255)]
    private $order_type;

    public function __construct()
    {
        $this->id_Adress = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdOrder(): ?Order
    {
        return $this->id_Order;
    }

    public function setIdOrder(?Order $id_Order): self
    {
        $this->id_Order = $id_Order;

        return $this;
    }

    /**
     * @return Collection|Address[]
     */
    public function getIdAdress(): Collection
    {
        return $this->id_Adress;
    }

    public function addIdAdress(Address $idAdress): self
    {
        if (!$this->id_Adress->contains($idAdress)) {
            $this->id_Adress[] = $idAdress;
            $idAdress->setOrderDelivery($this);
        }

        return $this;
    }

    public function removeIdAdress(Address $idAdress): self
    {
        if ($this->id_Adress->removeElement($idAdress)) {
            // set the owning side to null (unless already changed)
            if ($idAdress->getOrderDelivery() === $this) {
                $idAdress->setOrderDelivery(null);
            }
        }

        return $this;
    }

    public function getOrderType(): ?string
    {
        return $this->order_type;
    }

    public function setOrderType(string $order_type): self
    {
        $this->order_type = $order_type;

        return $this;
    }
}

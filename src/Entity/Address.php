<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\AdressRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AdressRepository::class)]
#[ApiResource(
    collectionOperations: [
        'post',
        'get' => [
            "security" => "is_granted('ROLE_ADMIN')"
        ],
    ],
)]
class Address
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 100)]
    private $address1;

    #[ORM\Column(type: 'string', length: 100)]
    private $address2;

    #[ORM\Column(type: 'string', length: 255)]
    private $country;

    #[ORM\Column(type: 'string', length: 255)]
    private $city;

    #[ORM\Column(type: 'string', length: 255)]
    private $zip_code;
    //truc

    #[ORM\ManyToOne(targetEntity: OrderDelivery::class, inversedBy: 'id_Adress')]
    #[ORM\JoinColumn(nullable: true)]
    private $orderDelivery;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'addresses')]
    #[ORM\JoinColumn(nullable: true)]
    private $customer;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAddress1(): ?string
    {
        return $this->address1;
    }

    public function setAddress1(string $address1): self
    {
        $this->address1 = $address1;

        return $this;
    }

    public function getAddress2(): ?string
    {
        return $this->address2;
    }

    public function setAddress2(string $address2): self
    {
        $this->address2 = $address2;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getZipCode(): ?string
    {
        return $this->zip_code;
    }

    public function setZipCode(string $zip_code): self
    {
        $this->zip_code = $zip_code;

        return $this;
    }

    public function getOrderDelivery(): ?OrderDelivery
    {
        return $this->orderDelivery;
    }

    public function setOrderDelivery(?OrderDelivery $orderDelivery): self
    {
        $this->orderDelivery = $orderDelivery;

        return $this;
    }

    public function getCustomer(): ?User
    {
        return $this->customer;
    }

    public function setCustomer(?User $customer): self
    {
        $this->customer = $customer;

        return $this;
    }
}

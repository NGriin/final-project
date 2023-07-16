<?php

namespace App\Entity;

use App\Repository\ProductSellerRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;

#[ORM\Entity(repositoryClass: ProductSellerRepository::class)]
class ProductSeller
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $cost = null;

    #[ORM\Column(nullable: true)]
    private ?int $cost_discount = null;

    #[ManyToOne(targetEntity: Product::class, inversedBy: 'productSeller')]
    #[JoinColumn(name: 'product_id', referencedColumnName: 'id')]
    private Product $product;

    #[ORM\OneToOne(targetEntity: Seller::class)]
    private Seller $seller;

    public function __construct(Product $product, Seller $seller)
    {
        $this->product = $product;
        $this->seller = $seller;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCost(): ?int
    {
        return $this->cost;
    }

    public function setCost(int $cost): self
    {
        $this->cost = $cost;

        return $this;
    }

    public function getCostDiscount(): ?int
    {
        return $this->cost_discount;
    }

    public function setCostDiscount(?int $cost_discount): self
    {
        $this->cost_discount = $cost_discount;

        return $this;
    }

    /**
     * @return Product
     */
    public function getProduct(): Product
    {
        return $this->product;
    }

    /**
     * @param Product $product
     */
    public function setProduct(Product $product): void
    {
        $this->product = $product;
    }

    /**
     * @return Seller
     */
    public function getSeller(): Seller
    {
        return $this->seller;
    }

    /**
     * @param Seller $seller
     */
    public function setSeller(Seller $seller): void
    {
        $this->seller = $seller;
    }
}

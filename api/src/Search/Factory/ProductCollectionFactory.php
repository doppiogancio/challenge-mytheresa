<?php

namespace App\Search\Factory;

use App\Search\Dto\Price;
use App\Search\Dto\Product;
use App\Entity\Product as ProductEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

readonly class ProductCollectionFactory
{
    /**
     * @param ProductEntity[] $products
     * @return Collection<Product>
     */
    public function create(array $products): Collection
    {
        return new ArrayCollection(
            array_map(function (array $product) {
                $price = new Price(
                    original: $product['price'],
                    final: $product['price'],
                );
                $price->applyDiscount($product['discount']);

                return new Product(
                    $product['id'],
                    $product['sku'],
                    $product['name'],
                    $product['category'],
                    $price
                );
            }, $products)
        );
    }
}
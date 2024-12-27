<?php

namespace App\Search\Factory;

use App\Entity\Product as ProductEntity;
use App\Search\Dto\Price;
use App\Search\Dto\Product;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

readonly class ProductCollectionFactory
{
    /**
     * @param ProductEntity[] $products
     *
     * @return Collection<Product>
     */
    public function create(array $products): Collection
    {
        return new ArrayCollection(
            array_map(function (array $product) {
                return new Product(
                    $product['id'],
                    $product['sku'],
                    $product['name'],
                    $product['category'],
                    new Price(
                        original: $product['price'],
                        discount: $product['discount'],
                    )
                );
            }, $products)
        );
    }
}

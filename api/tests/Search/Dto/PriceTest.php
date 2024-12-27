<?php

namespace App\Tests\Search\Dto;


use App\Search\Dto\Price;
use App\Search\Dto\Product;
use PHPUnit\Framework\TestCase;

class PriceTest extends TestCase
{
    public function testApplyNullDiscount(): void
    {
        $product = new Product(
            id: 321,
            sku: 'SKU123',
            name: 'Test Product',
            category: 'Test Category',
            price: new Price(
                original: 10000,
                final: 10000,
                discountPercentage: null,
                currency: 'USD'
            )
        );

        $product->applyDiscount(0);

        self::assertEquals(new Price(
            original: 10000,
            final: 10000,
            discountPercentage: null,
            currency: 'USD'
        ), $product->price);
    }

    public function testApplyDiscount(): void
    {
        $product = new Product(
            id: 321,
            sku: 'SKU123',
            name: 'Test Product',
            category: 'Test Category',
            price: new Price(
                original: 10000,
                final: 10000,
                discountPercentage: null,
                currency: 'USD'
            )
        );

        $product->applyDiscount(65);

        self::assertEquals(new Price(
            original: 10000,
            final: 3500,
            discountPercentage: '65%',
            currency: 'USD'
        ), $product->price);
    }
}

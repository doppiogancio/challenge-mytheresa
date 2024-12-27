<?php

namespace App\Search\Dto;

readonly class Product
{
    public function __construct(
        public int $id,
        public string $sku,
        public string $name,
        public string $category,
        public Price $price,
    ) {
    }
}

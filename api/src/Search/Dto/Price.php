<?php

namespace App\Search\Dto;

class Price
{
    public function __construct(
        private readonly int    $original,
        private int             $final,
        private ?string         $discountPercentage = null,
        private readonly string $currency = 'EUR',
    ) {
    }

    public function getOriginal(): int
    {
        return $this->original;
    }

    public function getFinal(): int
    {
        return $this->final;
    }

    public function getDiscountPercentage(): string
    {
        return $this->discountPercentage;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function applyDiscount(?int $discount): void
    {
        if (!$discount) {
            return;
        }

        $this->final = ($this->getOriginal() * (100 - $discount)) / 100;
        $this->discountPercentage = sprintf('%d%%', $discount);
    }
}
<?php

namespace App\Search\Dto;

readonly class Price
{
    private int $original;
    private int $final;
    private ?string $discountPercentage;
    private string $currency;

    public function __construct(int $original, ?int $discount, string $currency = 'EUR')
    {
        $this->original = $original;
        $final = $original;
        $discountPercentage = null;

        if ((int) $discount > 0) {
            $final = $original * (100 - $discount) / 100;
            $discountPercentage = $discount.'%';
        }

        $this->discountPercentage = $discountPercentage;
        $this->final = $final;
        $this->currency = $currency;
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
}

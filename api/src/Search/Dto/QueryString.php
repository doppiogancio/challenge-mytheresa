<?php

namespace App\Search\Dto;

use JMS\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;

readonly class QueryString
{
    public function __construct(
        public ?string $category = null,

        #[SerializedName('priceLessThan')]
        public ?int    $priceLessThan = null,
        public int     $page = 1,

        #[Assert\LessThanOrEqual(5)]
        #[SerializedName('pageSize')]
        public int     $pageSize = 5,
    ) {
    }
}
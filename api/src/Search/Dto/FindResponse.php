<?php

namespace App\Search\Dto;

use Doctrine\Common\Collections\Collection;

readonly class FindResponse
{
    /**
     * @param Collection<Product> $products
     */
    public function __construct(
        public QueryString $queryString,
        public Pagination $pagination,
        public Collection $products
    ) {
    }
}
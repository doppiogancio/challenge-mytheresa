<?php

namespace App\Search\Dto;

use Exception;
readonly class Pagination
{
    /**
     * @throws Exception
     */
    public function __construct(
        public int $totalItems = 0,
        public int $itemsPerPage = 5,
        public int $totalPages = 1,
        public int $currentPage = 1,
        public ?string $prevPage = null,
        public ?string $nextPage = null,
    ) {
        if ($totalItems < 0) {
            throw new Exception('The total number of items can not be negative');
        }

        if ($itemsPerPage < 1) {
            throw new Exception('The number of items per page has to be positive');
        }

        if ($itemsPerPage > 5) {
            throw new Exception('Maximum 5 items per page');
        }

        if ($totalPages < 1) {
            throw new Exception('The total number of pages has to be positive');
        }

        if ($currentPage < 1) {
            throw new Exception('The current page has to be positive');
        }
    }
}

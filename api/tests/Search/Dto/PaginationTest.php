<?php

namespace App\Tests\Search\Dto;

use App\Search\Dto\Pagination;
use PHPUnit\Framework\TestCase;

class PaginationTest extends TestCase
{
    public function testNegativeTotalItems(): void
    {
        self::expectExceptionMessage('The total number of items can not be negative');
        new Pagination(totalItems: -1);
    }

    public function testNonPositiveItemsPerPage(): void
    {
        self::expectExceptionMessage('The number of items per page has to be positive');
        new Pagination(itemsPerPage: 0);
    }

    public function testMaxItemsPerPage(): void
    {
        self::expectExceptionMessage('Maximum 5 items per page');
        new Pagination(itemsPerPage: 6);
    }

    public function testNonPositiveTotalPages(): void
    {
        self::expectExceptionMessage('The total number of pages has to be positive');
        new Pagination(totalPages: 0);
    }

    public function testNonPositiveCurrentPage(): void
    {
        self::expectExceptionMessage('The current page has to be positive');
        new Pagination(currentPage: 0);
    }
}

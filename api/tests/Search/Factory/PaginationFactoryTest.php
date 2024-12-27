<?php

namespace App\Tests\Search\Factory;

use App\Search\Dto\Pagination;
use App\Search\Dto\QueryString;
use App\Search\Factory\PaginationFactory;
use Exception;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use PHPUnit\Framework\Attributes\DataProvider;


class PaginationFactoryTest extends WebTestCase
{
    private PaginationFactory $factory;

    protected function setUp(): void
    {
        parent::setUp();

        /** @var PaginationFactory $factory */
        $factory = self::getContainer()->get(PaginationFactory::class);
        $this->factory = $factory;
    }

    /**
     * @throws Exception
     */
    #[DataProvider('paginationDataProvider')]
    public function testCreate(QueryString $queryString, int $totalItems, Pagination $expectedDto): void
    {
        $dto = $this->factory->create($queryString, $totalItems);
        self::assertEquals($expectedDto, $dto);
    }

    /**
     * @throws Exception
     */
    static public function paginationDataProvider(): array
    {
        return [
            [
                'queryString' => new QueryString(category: 'shoes', page: 3, pageSize: 4),
                'totalItems' => 132,
                'expectedDto' => new Pagination(
                    totalItems: 132,
                    itemsPerPage: 4,
                    totalPages: 33,
                    currentPage: 3,
                    prevPage: 'http://localhost/products?page=2&pageSize=4&category=shoes',
                    nextPage: 'http://localhost/products?page=4&pageSize=4&category=shoes'
                )
            ],
            [
                'queryString' => new QueryString(category: 'shoes', page: 1, pageSize: 4),
                'totalItems' => 132,
                'expectedDto' => new Pagination(
                    totalItems: 132,
                    itemsPerPage: 4,
                    totalPages: 33,
                    currentPage: 1,
                    prevPage: null,
                    nextPage: 'http://localhost/products?page=2&pageSize=4&category=shoes'
                )
            ],
            [
                'queryString' => new QueryString(category: 'shoes', page: 33, pageSize: 4),
                'totalItems' => 132,
                'expectedDto' => new Pagination(
                    totalItems: 132,
                    itemsPerPage: 4,
                    totalPages: 33,
                    currentPage: 33,
                    prevPage: 'http://localhost/products?page=32&pageSize=4&category=shoes',
                    nextPage: null
                )
            ],
        ];
    }
}

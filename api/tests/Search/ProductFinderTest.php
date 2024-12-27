<?php

namespace App\Tests\Search;

use App\Search\Dto\Pagination;
use App\Search\Dto\Price;
use App\Search\Dto\Product;
use App\Search\Dto\QueryString;
use App\Search\ProductFinder;
use App\Tests\Fixtures\TestFixtures;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Exception;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;
use PHPUnit\Framework\Attributes\DataProvider;

class ProductFinderTest extends WebTestCase
{
    protected AbstractDatabaseTool $databaseTool;
    private ProductFinder $finder;

    protected function setUp(): void
    {
        /** @var DatabaseToolCollection $databaseTool */
        $databaseTool = static::getContainer()->get(DatabaseToolCollection::class);
        $this->databaseTool = $databaseTool->get();

        $this->databaseTool->loadFixtures([
            TestFixtures::class,
        ]);

        /** @var ProductFinder $finder */
        $finder = self::getContainer()->get(ProductFinder::class);
        $this->finder = $finder;
    }


    /**
     * @throws Exception
     */
    #[DataProvider('responseDataProvider')]
    public function testFindAll(QueryString $queryString, Pagination $expectedPagination, Collection $expectedProducts): void
    {
        $response = $this->finder->findAll($queryString);

        // Assert Pagination
        self::assertEquals($expectedPagination, $response->pagination);

        // Assert Products
        self::assertEquals($expectedProducts, $response->products);
    }

    static public function responseDataProvider(): array
    {
        return [
            [
                'queryString' => new QueryString(category: 'boots'),
                'expectedPagination' => new Pagination(
                    totalItems: 3,
                    itemsPerPage: 5,
                    totalPages: 1,
                    currentPage: 1,
                    prevPage: null,
                    nextPage: null,
                ),
                'expectedProducts' => new ArrayCollection([
                    new Product(
                        id: 1,
                        sku: '000001',
                        name: 'BV Lean leather ankle boots',
                        category: 'boots',
                        price: new Price(
                            original: 89000,
                            discount: 30,
                        )
                    ),
                    new Product(
                        id: 2,
                        sku: '000002',
                        name: 'BV Lean leather ankle boots',
                        category: 'boots',
                        price: new Price(
                            original: 99000,
                            discount: 30,
                        )
                    ),
                    new Product(
                        id: 3,
                        sku: '000003',
                        name: 'BV Lean leather ankle boots',
                        category: 'boots',
                        price: new Price(
                            original: 71000,
                            discount: 30,
                        )
                    ),
                ])
            ]
        ];
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        unset($this->databaseTool);
    }
}

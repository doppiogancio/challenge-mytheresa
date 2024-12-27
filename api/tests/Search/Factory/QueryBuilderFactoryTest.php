<?php

namespace App\Tests\Search\Factory;

use App\Entity\Product;
use App\Search\Dto\QueryString;
use App\Search\Factory\QueryBuilderFactory;
use App\Tests\Fixtures\TestFixtures;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;
use PHPUnit\Framework\Attributes\DataProvider;

class QueryBuilderFactoryTest extends WebTestCase
{
    protected AbstractDatabaseTool $databaseTool;
    private QueryBuilderFactory $factory;

    protected function setUp(): void
    {
        parent::setUp();

        /** @var QueryBuilderFactory $factory */
        $factory = self::getContainer()->get(QueryBuilderFactory::class);
        $this->factory = $factory;

        /** @var DatabaseToolCollection $databaseTool */
        $databaseTool = static::getContainer()->get(DatabaseToolCollection::class);
        $this->databaseTool = $databaseTool->get();

        $this->databaseTool->loadFixtures([
            TestFixtures::class,
        ]);
    }

    /**
     * @param QueryString $queryString
     * @param string[] $expectedSkus
     * @return void
     */
    #[DataProvider('queryBuilderProvider')]
    public function testCreateQueryBuilder(QueryString $queryString, array $expectedSkus): void
    {
        $builder = $this->factory->createWithPaginationAndSorting($queryString);
        self::assertEquals($expectedSkus, array_map(function (array $product): string {
            return $product['sku'];
        }, $builder->getQuery()->getResult()));
    }

    static public function queryBuilderProvider(): array
    {
        return [
            [
                'queryString' => new QueryString(category: 'boots'),
                'expectedSkus' => [
                    '000001',
                    '000002',
                    '000003',
                ]
            ],
            [
                'queryString' => new QueryString(category: 'glasses'),
                'expectedSkus' => [
                    '000006',
                ]
            ],
            [
                'queryString' => new QueryString(priceLessThan: 60000),
                'expectedSkus' => [
                    '000005',
                    '000006',
                ]
            ],
        ];
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        unset($this->databaseTool);
    }
}

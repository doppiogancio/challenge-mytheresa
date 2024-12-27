<?php

namespace App\Tests\Controller;

use App\Tests\Fixtures\TestFixtures;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiControllerTest extends WebTestCase
{
    protected AbstractDatabaseTool $databaseTool;

    private KernelBrowser $client;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = static::createClient();

        /** @var DatabaseToolCollection $databaseTool */
        $databaseTool = static::getContainer()->get(DatabaseToolCollection::class);
        $this->databaseTool = $databaseTool->get();

        $this->databaseTool->loadFixtures([
            TestFixtures::class,
        ]);
    }

    public function testResponseStructure(): void
    {
        // Request a specific page
        $this->client->request('GET', '/products');

        // Validate a successful response and some content
        $this->assertResponseIsSuccessful();
        $response = $this->client->getResponse();
        $json = json_decode($response->getContent(), true);

        self::assertArrayHasKey('query_string', $json);
        self::assertArrayHasKey('pagination', $json);
        self::assertArrayHasKey('products', $json);

        // Defaults values
        self::assertEquals([
            'category' => null,
            'priceLessThan' => null,
            'page' => 1,
            'pageSize' => 5,
        ], $json['query_string']);

        self::assertEquals([
            'total_items' => 6,
            'items_per_page' => 5,
            'total_pages' => 2,
            'current_page' => 1,
            'prev_page' => null,
            'next_page' => 'http://localhost/products?page=2&pageSize=5',
        ], $json['pagination']);

        self::assertCount(5, $json['products']);
    }

    #[DataProvider('discountsDataProvider')]
    public function testDiscounts(string $url, int $original, int $final, ?string $percentage): void
    {
        $this->client->request('GET', $url);

        // Validate a successful response and some content
        $this->assertResponseIsSuccessful();
        $response = $this->client->getResponse();

        $json = json_decode($response->getContent(), true);

        self::assertEquals([
            'original' => $original,
            'final' => $final,
            'discount_percentage' => $percentage,
            'currency' => 'EUR',
        ], $json['products'][0]['price']);
    }

    static public function discountsDataProvider(): array
    {
        return [
            [
                'url' => '/products?category=boots',
                'original' => 89000,
                'final' => 62300,
                'percentage' => '30%',
            ],
            [
                'url' => '/products?category=glasses',
                'original' => 49000,
                'final' => 41650,
                'percentage' => '15%',
            ],
            [
                'url' => '/products?category=sandals',
                'original' => 79500,
                'final' => 79500,
                'percentage' => null,
            ],
        ];
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        unset($this->databaseTool);
    }
}

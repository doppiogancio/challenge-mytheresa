<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Discount;
use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture implements FixtureGroupInterface
{
    public static function getGroups(): array
    {
        return ['api', 'large'];
    }

    public function load(ObjectManager $manager): void
    {
        /** @var array<string,Category> $categories */
        $categories = [];

        foreach (['boots', 'sandals', 'sneakers'] as $categoryName) {
            $category = new Category();
            $category->setName($categoryName);

            $manager->persist($category);
            $categories[$categoryName] = $category;
        }

        /** @var array<string,Product> $product */
        $products = [];
        $json = json_decode(file_get_contents(__DIR__ . '/fixtures/products.json'), true);
        foreach ($json as $p) {
            $product = $this->createProduct(
                sku: $p['sku'],
                name: $p['name'],
                price: $p['price'],
                category: $categories[$p['category']],
            );

            $products[$p['sku']] = $product;
            $manager->persist($product);
        }

        $discount = new Discount();
        $discount->setCategory($categories['boots']);
        $discount->setPercentage(30);
        $manager->persist($discount);

        $discount = new Discount();
        $discount->setProduct($products['000003']);
        $discount->setPercentage(15);
        $manager->persist($discount);

        $manager->flush();
    }

    private function createProduct(string $sku, string $name, int $price, Category $category): Product
    {
        $product = new Product();
        $product->setSku($sku);
        $product->setName($name);
        $product->setCategory($category);
        $product->setPrice($price);

        return $product;
    }
}

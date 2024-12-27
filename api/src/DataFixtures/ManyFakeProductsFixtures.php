<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Discount;
use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class ManyFakeProductsFixtures extends Fixture implements FixtureGroupInterface
{
    public static function getGroups(): array
    {
        return ['large'];
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        $categoryNames = [
            'Accessories',
            'Backpacks',
            'Belts',
            'Coats',
            'Dresses',
            'Gloves',
            'Hats',
            'Hoodies',
            'Jackets',
            'Jeans',
            'Jewelry',
            'Pants',
            'Scarves',
            'Shirts',
            'Shorts',
            'Skirts',
            'Socks',
            'Sunglasses',
            'Sweaters',
            'T-shirts',
            'Watches',
            'Wallets',
        ];

        $categories = [];

        foreach ($categoryNames as $categoryName) {
            $category = new Category();
            $category->setName(strtolower($categoryName));

            $manager->persist($category);
            $categories[] = $category;

            foreach (range(1, 10) as $index) {
                $discount = new Discount();
                $discount->setCategory($category);
                $discount->setPercentage(rand() % 100);

                $manager->persist($discount);
            }
        }

        ini_set('memory_limit', '512M');
        srand(floor(time() / (60 * 60 * 24)));
        foreach ($this->generateProducts($categories, $faker) as $i => $product) {
            $manager->persist($product);

            $discount = new Discount();
            $discount->setProduct($product);
            $discount->setPercentage(rand() % 100);
            $manager->persist($discount);

            if (0 === $i % 100) {
                $manager->flush();
            }
        }

        $manager->flush();
    }

    private function generateProducts(array $categories, Generator $faker): \Generator
    {
        foreach (range(0, 20000) as $i) {
            $product = new Product();
            $product->setSku($faker->uuid());
            $product->setName($faker->name);

            $randomKey = array_rand($categories);
            $product->setCategory($categories[$randomKey]);
            $product->setPrice(rand(1010, 2000) * 100);

            yield $product;
        }
    }
}

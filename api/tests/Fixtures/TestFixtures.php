<?php

namespace App\Tests\Fixtures;

use App\Entity\Category;
use App\Entity\Discount;
use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TestFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $categories = [];

        foreach (['boots', 'sandals', 'sneakers', 'glasses'] as $categoryName) {
            $category = new Category();
            $category->setName($categoryName);

            $manager->persist($category);
            $categories[$categoryName] = $category;
        }

        $manager->persist($this->createProduct(
            sku: '000001',
            name: 'BV Lean leather ankle boots',
            price: 89000,
            category: $categories['boots'],
        ));

        $manager->persist($this->createProduct(
            sku: '000002',
            name: 'BV Lean leather ankle boots',
            price: 99000,
            category: $categories['boots'],
        ));

        $product000003 = $this->createProduct(
            sku: '000003',
            name: 'BV Lean leather ankle boots',
            price: 71000,
            category: $categories['boots'],
        );
        $manager->persist($product000003);

        $manager->persist($this->createProduct(
            sku: '000004',
            name: 'Naima embellished suede sandals',
            price: 79500,
            category: $categories['sandals'],
        ));

        $manager->persist($this->createProduct(
            sku: '000005',
            name: 'Nathane leather sneakers',
            price: 59000,
            category: $categories['sneakers'],
        ));

        $product000006 = $this->createProduct(
            sku: '000006',
            name: 'Sun glasses',
            price: 49000,
            category: $categories['glasses'],
        );
        $manager->persist($product000006);

        // Discounts
        $discount = new Discount();
        $discount->setCategory($categories['boots']);
        $discount->setPercentage(30);
        $manager->persist($discount);

        $discount = new Discount();
        $discount->setProduct($product000003);
        $discount->setPercentage(15);
        $manager->persist($discount);

        $discount = new Discount();
        $discount->setProduct($product000006);
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
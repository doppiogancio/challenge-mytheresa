<?php

namespace App\Search\Factory;

use App\Entity\Discount;
use App\Repository\ProductRepository;
use App\Search\Dto\QueryString;
use Doctrine\ORM\QueryBuilder;

readonly class QueryBuilderFactory
{
    public function __construct(
        private ProductRepository $productRepository
    ) {
    }

    public function createForTotals(QueryString $queryString): QueryBuilder
    {
        $subquery = $this->create($queryString)
            ->select('p.id');

        $qb = $this->productRepository->createQueryBuilder('t');
        $qb->select('count(t.id)');
        $qb->where($qb->expr()->in('t.id', $subquery->getQuery()->getDQL()));
        $qb->setParameters($subquery->getParameters());

        return $qb;
    }

    public function createWithPaginationAndSorting(QueryString $queryString): QueryBuilder
    {
        $qb = $this->create($queryString);

        // Add pagination
        if (!is_null($queryString->page) && !is_null($queryString->pageSize)) {
            $qb->setFirstResult(($queryString->page - 1) * $queryString->pageSize);
            $qb->setMaxResults($queryString->pageSize);
        }

        return $qb;
    }

    /**
     * VERY IMPORTANT: do not add pagination and sorting
     */
    private function create(QueryString $queryString): QueryBuilder
    {
        $qb = $this->productRepository->createQueryBuilder('p');
        $qb->join('p.category', 'c');

        $qb->leftJoin(
            Discount::class,
            'd',
            'WITH',
            '(d.category = c.id) OR (d.product = p.id)'
        );

        if (!is_null($queryString->category)) {
            $qb->andWhere('c.name = :category')
                ->setParameter('category', $queryString->category);
        }

        if (!is_null($queryString->priceLessThan)) {
            $qb->andWhere('p.price <= :maxPrice')
                ->setParameter('maxPrice', $queryString->priceLessThan);
        }

        $qb->addGroupBy('p.id');
        $qb->addGroupBy('c.id');

        $qb->select([
            'p.id',
            'p.sku',
            'p.name',
            'c.name as category',
            'p.price',
            'MAX(COALESCE(d.percentage, 0)) as discount',
        ]);

        return $qb;
    }
}

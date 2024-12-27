<?php

namespace App\Search;

use App\Search\Dto\FindResponse;
use App\Search\Dto\QueryString;
use App\Search\Factory\PaginationFactory;
use App\Search\Factory\ProductCollectionFactory;
use App\Search\Factory\QueryBuilderFactory;
use Doctrine\ORM\AbstractQuery;

readonly class ProductFinder
{
    public function __construct(
        private QueryBuilderFactory $queryBuilderFactory,
        private ProductCollectionFactory $productCollectionFactory,
        private PaginationFactory $paginationFactory,
    ) {
    }

    /**
     * @throws \Exception
     */
    public function findAll(QueryString $queryString): FindResponse
    {
        $totalItems = $this->getTotalItems($queryString);

        $qb = $this->queryBuilderFactory->createWithPaginationAndSorting($queryString);

        return new FindResponse(
            queryString: $queryString,
            pagination: $this->paginationFactory->create(
                queryString: $queryString,
                totalItems: $totalItems,
            ),
            products: $this->productCollectionFactory->create(
                $qb->getQuery()->getResult(AbstractQuery::HYDRATE_ARRAY)
            ),
        );
    }

    public function getTotalItems(QueryString $queryString): int
    {
        return $this->queryBuilderFactory->createForTotals($queryString)
            ->getQuery()
            ->getSingleScalarResult();
    }
}

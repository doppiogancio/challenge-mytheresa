<?php

namespace App\Search\Factory;

use App\Search\Dto\Pagination;
use App\Search\Dto\QueryString;
use Exception;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

readonly class PaginationFactory
{
    public function __construct(private UrlGeneratorInterface $router)
    {
    }

    /**
     * @throws Exception
     */
    public function create(QueryString $queryString, int $totalItems): Pagination
    {
        $totalPages = max(ceil($totalItems / $queryString->pageSize), 1);
        $currentPage = $queryString->page;

        return new Pagination(
            totalItems: $totalItems,
            itemsPerPage: $queryString->pageSize,
            totalPages: $totalPages,
            currentPage: $currentPage,
            prevPage: $currentPage > 1 ? $this->generatePrevUrl($queryString) : null,
            nextPage: $currentPage < $totalPages ? $this->generateNextUrl($queryString) : null,
        );
    }

    /**
     * @param QueryString $queryString
     * @return string
     */
    public function generatePrevUrl(QueryString $queryString): string
    {
        return $this->router->generate('api_products', array_filter([
            'page' => $queryString->page - 1,
            'pageSize' => $queryString->pageSize,
            'category' => $queryString->category,
            'priceLessThan' => $queryString->priceLessThan,
        ]), UrlGeneratorInterface::ABSOLUTE_URL);
    }

    /**
     * @param QueryString $queryString
     * @return string
     */
    public function generateNextUrl(QueryString $queryString): string
    {
        return $this->router->generate('api_products', array_filter([
            'page' => $queryString->page + 1,
            'pageSize' => $queryString->pageSize,
            'category' => $queryString->category,
            'priceLessThan' => $queryString->priceLessThan,
        ]), UrlGeneratorInterface::ABSOLUTE_URL);
    }
}

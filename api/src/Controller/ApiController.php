<?php

namespace App\Controller;

use App\Search\Dto\FindResponse;
use App\Search\Dto\QueryString;
use App\Search\ProductFinder;
use FOS\RestBundle\View\View;
use Psr\Cache\InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Attribute\Route;
use Nelmio\ApiDocBundle\Attribute as ApiDoc;
use OpenApi\Attributes as OA;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class ApiController extends AbstractController
{
    public function __construct(
        private readonly ProductFinder $productFinder,
        private readonly CacheInterface $cache,
        private readonly int $ttl,
    ) {
    }

    /**
     * @throws InvalidArgumentException
     */
    #[OA\Response(
        response: 200,
        description: 'Search results',
        content: new ApiDoc\Model(type: FindResponse::class)
    )]
    #[Route('/products', name: 'api_products', methods: ['GET'])]
    public function products(#[MapQueryString] QueryString $queryString = new QueryString()): View
    {
        $cacheKey = md5(serialize($queryString));
        $response = $this->cache->get($cacheKey, function (ItemInterface $item) use ($queryString): FindResponse {
            $item->expiresAfter($this->ttl);

            return $this->productFinder->findAll($queryString);
        });

        return View::create($response);
    }

    #[Route('/debug', name: 'api_debug', methods: ['GET'])]
    public function debugAction(): void
    {
        phpinfo();
    }
}
<?php

declare(strict_types=1);

namespace cv65kr\SyliusPersonalizedProducts\Transformer;

use Sylius\Component\Core\Model\Product;
use Sylius\Component\Resource\Repository\RepositoryInterface;

/**
 * @author Kajetan Kołtuniak <kajetan@koltuniak.com>
 */
class ProductTransformer
{
    /**
     * @var RepositoryInterface
     */
    private $productRepository;

    /**
     * ProductTransformer constructor.
     *
     * @param RepositoryInterface $productRepository
     */
    public function __construct(RepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * @param array $response
     *
     * @return array
     *
     * @throws \Exception
     */
    public function parseResponse(array $response): array
    {
        if (!isset($response['itemScores'])) {
            throw new \Exception('Key "itemScores" not found.');
        }

        $products = [];
        foreach ($response['itemScores'] as $item) {
            $transformProduct = $this->transform(
                (int) $item['item']
            );
            if (null !== $transformProduct) {
                $products[] = $transformProduct;
            }
        }

        return $products;
    }

    /**
     * @param int $id
     *
     * @return Product|null
     */
    private function transform(int $id): ?Product
    {
        return $this->productRepository->find($id);
    }
}

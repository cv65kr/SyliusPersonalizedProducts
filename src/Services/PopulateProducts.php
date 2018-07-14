<?php

declare(strict_types=1);

namespace cv65kr\SyliusPersonalizedProducts\Services;

use cv65kr\SyliusPersonalizedProducts\Factory\PredictionConnectFactory;
use Sylius\Component\Core\Model\Product;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Kajetan Kołtuniak <kajetan@koltuniak.com>
 */
class PopulateProducts implements PopulateProductsInterface, PopulateInterface
{
    /**
     * @var PredictionConnectFactory
     */
    private $connectFactory;

    /**
     * @var RepositoryInterface
     */
    private $productRepository;

    /**
     * PopulateProducts constructor.
     *
     * @param PredictionConnectFactory $connectFactory
     */
    public function __construct(PredictionConnectFactory $connectFactory, RepositoryInterface $productRepository)
    {
        $this->connectFactory = $connectFactory;
        $this->productRepository = $productRepository;
    }

    /**
     * @param OutputInterface $output
     */
    public function populate(OutputInterface $output): void
    {
        $output->writeln('Populate products:');

        $model = $this->productRepository->findAll();
        if (null === $model) {
            $output->writeln('No products.');

            return;
        }

        /** @var Product $product */
        foreach ($model as $product) {
            if ($product->isEnabled()) {
                $status = $this->populateSingle($product) ? '<info>OK</info>' : '<error>ERROR</error>';
                $output->writeln(
                    \sprintf('Populate product "%s" - %s', $product->getName(), $status)
                );
            } else {
                $status = $this->removeSingle($product) ? '<info>OK</info>' : '<error>ERROR</error>';
                $output->writeln(
                    \sprintf('Remove product "%s" - %s', $product->getName(), $status)
                );
            }
        }
    }

    /**
     * @param ProductInterface $product
     *
     * @return bool
     *
     * @throws \predictionio\PredictionIOAPIError
     */
    public function populateSingle(ProductInterface $product): bool
    {
        $response = $this->connectFactory->getEventClient()->setItem(
            $product->getId(),
            [
                'code' => $product->getCode(),
                'name' => $product->getName(),
                'description' => $product->getDescription(),
            ]
        );

        return isset($response['eventId']);
    }

    /**
     * @param ProductInterface $product
     *
     * @return bool
     *
     * @throws \predictionio\PredictionIOAPIError
     */
    public function removeSingle(ProductInterface $product): bool
    {
        $response = $this->connectFactory->getEventClient()->deleteItem(
            $product->getId()
        );

        return isset($response['eventId']);
    }
}

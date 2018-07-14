<?php

declare(strict_types=1);

namespace cv65kr\SyliusPersonalizedProducts\Services;

use cv65kr\SyliusPersonalizedProducts\Factory\PredictionConnectFactory;
use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Kajetan Kołtuniak <kajetan@koltuniak.com>
 */
class PopulateCustomers implements PopulateCustomersInterface, PopulateInterface
{
    /**
     * @var PredictionConnectFactory
     */
    private $connectFactory;

    /**
     * @var RepositoryInterface
     */
    private $customerRepository;

    /**
     * PopulateCustomers constructor.
     *
     * @param PredictionConnectFactory $connectFactory
     * @param RepositoryInterface $customerRepository
     */
    public function __construct(PredictionConnectFactory $connectFactory, RepositoryInterface $customerRepository)
    {
        $this->connectFactory = $connectFactory;
        $this->customerRepository = $customerRepository;
    }

    /**
     * @param OutputInterface $output
     */
    public function populate(OutputInterface $output): void
    {
        $output->writeln('Populate customers:');

        $model = $this->customerRepository->findAll();
        if (null === $model) {
            $output->writeln('No customers.');

            return;
        }

        /** @var CustomerInterface $customer */
        foreach ($model as $customer) {
            $status = $this->populateSingle($customer) ? '<info>OK</info>' : '<error>ERROR</error>';
            $output->writeln(
                \sprintf('Populate customer "%d" - %s', $customer->getId(), $status)
            );
        }
    }

    /**
     * @param CustomerInterface $customer
     *
     * @return bool
     *
     * @throws \predictionio\PredictionIOAPIError
     */
    public function populateSingle(CustomerInterface $customer): bool
    {
        $response = $this->connectFactory->getEventClient()->setUser(
            $customer->getId()
        );

        return isset($response['eventId']);
    }

    /**
     * @param CustomerInterface $customer
     *
     * @return bool
     *
     * @throws \predictionio\PredictionIOAPIError
     */
    public function removeSingle(CustomerInterface $customer): bool
    {
        $response = $this->connectFactory->getEventClient()->deleteUser(
            $product->getId()
        );

        return isset($response['eventId']);
    }
}

<?php

declare(strict_types=1);

namespace cv65kr\SyliusPersonalizedProducts\Command;

use cv65kr\SyliusPersonalizedProducts\Services\PopulateCustomersInterface;
use cv65kr\SyliusPersonalizedProducts\Services\PopulateProductsInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Kajetan Kołtuniak <kajetan@koltuniak.com>
 */
final class PopulateCommand extends Command
{
    /**
     * @var PopulateProductsInterface
     */
    private $populateProducts;

    /**
     * @var PopulateCustomersInterface
     */
    private $populateCustomers;

    /**
     * PopulateCommand constructor.
     *
     * @param PopulateProductsInterface $populateProducts
     * @param PopulateCustomersInterface $populateCustomers
     */
    public function __construct(
        PopulateProductsInterface $populateProducts,
        PopulateCustomersInterface $populateCustomers
    ) {
        parent::__construct();
        $this->populateProducts = $populateProducts;
        $this->populateCustomers = $populateCustomers;
    }

    protected function configure(): void
    {
        $this->setName('sylius:product_personalize:populate');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $this->populateCustomers->populate($output);
        $this->populateProducts->populate($output);
    }
}

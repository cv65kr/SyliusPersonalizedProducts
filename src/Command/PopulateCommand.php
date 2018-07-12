<?php

declare(strict_types=1);

namespace cv65kr\SyliusPersonalizedProducts\Command;

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
     * PopulateCommand constructor.
     *
     * @param PopulateProductsInterface $populateProducts
     */
    public function __construct(PopulateProductsInterface $populateProducts)
    {
        parent::__construct();
        $this->populateProducts = $populateProducts;
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
        $this->populateProducts->populate($output);
    }
}
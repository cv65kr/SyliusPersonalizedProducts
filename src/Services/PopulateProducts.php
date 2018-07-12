<?php

declare(strict_types=1);

namespace cv65kr\SyliusPersonalizedProducts\Services;

use cv65kr\SyliusPersonalizedProducts\Factory\PredictionConnectFactory;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Kajetan Kołtuniak <kajetan@koltuniak.com>
 */
class PopulateProducts implements PopulateProductsInterface
{
    /**
     * @var PredictionConnectFactory
     */
    private $connectFactory;

    /**
     * PopulateProducts constructor.
     *
     * @param PredictionConnectFactory $connectFactory
     */
    public function __construct(PredictionConnectFactory $connectFactory)
    {
        $this->connectFactory = $connectFactory;
    }

    /**
     * @param OutputInterface $output
     */
    public function populate(OutputInterface $output): void
    {
        $output->writeln('test');
    }
}
<?php

declare(strict_types=1);

namespace cv65kr\SyliusPersonalizedProducts\Services;

use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Kajetan Kołtuniak <kajetan@koltuniak.com>
 */
interface PopulateInterface
{
    /**
     * @param OutputInterface $output
     */
    public function populate(OutputInterface $output): void;
}

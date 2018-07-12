<?php

declare(strict_types=1);

namespace cv65kr\SyliusPersonalizedProducts\Factory;

use predictionio\EngineClient;
use predictionio\EventClient;

/**
 * @author Kajetan Kołtuniak <kajetan@koltuniak.com>
 */
final class PredictionConnectFactory
{
    /**
     * @var string
     */
    private $eventHost;

    /**
     * @var string
     */
    private $eventPort;

    /**
     * @var string
     */
    private $engineHost;

    /**
     * @var string
     */
    private $enginePort;

    /**
     * @var string
     */
    private $predictionKey;

    /**
     * PredictionConnectFactory constructor.
     *
     * @param string $eventHost
     * @param string $eventPort
     * @param string $engineHost
     * @param string $enginePort
     * @param string $predictionKey
     */
    public function __construct(
        string $eventHost,
        string $eventPort,
        string $engineHost,
        string $enginePort,
        string $predictionKey
    ) {
        $this->eventHost = $eventHost;
        $this->eventPort = $eventPort;
        $this->engineHost = $engineHost;
        $this->enginePort = $enginePort;
        $this->predictionKey = $predictionKey;
    }

    /**
     * @return EventClient
     */
    public function getEventClient(): EventClient
    {
        $host = \sprintf(
            'http://%s:%d',
            $this->eventHost,
            $this->eventPort
        );

        return new EventClient($host, $this->predictionKey);
    }

    /**
     * @return EngineClient
     */
    public function getEngineClient(): EngineClient
    {
        $host = \sprintf(
            'http://%s:%d',
            $this->engineHost,
            $this->enginePort
        );

        return new EngineClient($host);
    }
}
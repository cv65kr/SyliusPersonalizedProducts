<?php

declare(strict_types=1);

namespace cv65kr\SyliusPersonalizedProducts\EventListener;

use cv65kr\SyliusPersonalizedProducts\Factory\PredictionConnectFactory;
use cv65kr\SyliusPersonalizedProducts\PredictionEventInterface;
use cv65kr\SyliusPersonalizedProducts\Services\PopulateProductsInterface;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Customer\Context\CustomerContextInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

/**
 * @author Kajetan Kołtuniak <kajetan@koltuniak.com>
 */
class ProductListener
{
    /**
     * @var CustomerContextInterface
     */
    private $customerContext;

    /**
     * @var PredictionConnectFactory
     */
    private $predictionConnectFactory;

    /**
     * @var PopulateProductsInterface
     */
    private $populateProducts;

    /**
     * ProductListener constructor.
     *
     * @param CustomerContextInterface $customerContext
     * @param PredictionConnectFactory $predictionConnectFactory
     * @param PopulateProductsInterface $populateProducts
     */
    public function __construct(
        CustomerContextInterface $customerContext,
        PredictionConnectFactory $predictionConnectFactory,
        PopulateProductsInterface $populateProducts
    ) {
        $this->customerContext = $customerContext;
        $this->predictionConnectFactory = $predictionConnectFactory;
        $this->populateProducts = $populateProducts;
    }

    /**
     * @param GenericEvent $event
     */
    public function onProductShow(GenericEvent $event): void
    {
        /** @var ProductInterface $subject */
        $subject = $event->getSubject();
        if (null === $subject) {
            return;
        }

        $customer = $this->customerContext->getCustomer();
        if (null === $customer) {
            return;
        }

        $this->predictionConnectFactory->getEventClient()->recordUserActionOnItem(
            PredictionEventInterface::PREDICT_PRODUCT,
            $customer->getId(),
            $subject->getId()
        );
    }

    /**
     * @param GenericEvent $event
     */
    public function onProductCreate(GenericEvent $event): void
    {
        /** @var ProductInterface $subject */
        $subject = $event->getSubject();
        if (null === $subject) {
            return;
        }

        if (false === $subject->isEnabled()) {
            return;
        }

        $this->populateProducts->populateSingle($subject);
    }

    /**
     * @param GenericEvent $event
     */
    public function onProductUpdate(GenericEvent $event): void
    {
        /** @var ProductInterface $subject */
        $subject = $event->getSubject();
        if (null === $subject) {
            return;
        }

        if (false === $subject->isEnabled()) {
            $this->populateProducts->removeSingle($subject);

            return;
        }

        $this->populateProducts->populateSingle($subject);
    }

    /**
     * @param GenericEvent $event
     */
    public function onProductRemove(GenericEvent $event): void
    {
        /** @var ProductInterface $subject */
        $subject = $event->getSubject();
        if (null === $subject) {
            return;
        }

        $this->populateProducts->removeSingle($subject);
    }
}

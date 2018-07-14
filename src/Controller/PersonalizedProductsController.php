<?php

declare(strict_types=1);

namespace cv65kr\SyliusPersonalizedProducts\Controller;

use cv65kr\SyliusPersonalizedProducts\Factory\PredictionConnectFactory;
use cv65kr\SyliusPersonalizedProducts\Transformer\ProductTransformer;
use Sylius\Component\Customer\Context\CustomerContextInterface;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Webmozart\Assert\Assert;

/**
 * @author Kajetan Kołtuniak <kajetan@koltuniak.com>
 */
class PersonalizedProductsController
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
     * @var EngineInterface
     */
    private $templatingEngine;

    /**
     * @var ProductTransformer
     */
    private $productTransformer;

    /**
     * PersonalizedProductsController constructor.
     *
     * @param CustomerContextInterface $customerContext
     * @param PredictionConnectFactory $predictionConnectFactory
     * @param EngineInterface $templatingEngine
     * @param ProductTransformer $productTransformer
     */
    public function __construct(
        CustomerContextInterface $customerContext,
        PredictionConnectFactory $predictionConnectFactory,
        EngineInterface $templatingEngine,
        ProductTransformer $productTransformer
    ) {
        $this->customerContext = $customerContext;
        $this->predictionConnectFactory = $predictionConnectFactory;
        $this->templatingEngine = $templatingEngine;
        $this->productTransformer = $productTransformer;
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function __invoke(Request $request): Response
    {
        $limit = $request->get('limit');
        Assert::greaterThan($limit, 0);

        $template = $request->get('template');
        Assert::notEmpty($template);

        $products = [];
        $customer = $this->customerContext->getCustomer();
        if (null !== $customer) {
            try {
                $response = $this->predictionConnectFactory->getEngineClient()->sendQuery(
                    [
                        'user' => $customer->getId(),
                        'num' => $limit,
                    ]
                );

                $products = $this->productTransformer->parseResponse($response);
            } catch (\Exception $e) {
            }
        }

        return $this->templatingEngine->renderResponse(
            $template,
            [
                'products' => $products,
            ]
        );
    }
}

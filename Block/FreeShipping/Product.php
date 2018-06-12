<?php

namespace Creativestyle\ProductPositiveIndicatorsExtension\Block\FreeShipping;

class Product extends \Magento\Framework\View\Element\Template
{
    protected $_template = 'Creativestyle_ProductPositiveIndicatorsExtension::freeshipping/product.phtml';

    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;

    /**
     * @var \Creativestyle\ProductPositiveIndicatorsExtension\Service\FreeShippingInterface
     */
    protected $freeShippingService;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Creativestyle\ProductPositiveIndicatorsExtension\Service\FreeShippingInterface $freeShippingService,
        array $data = []
    )
    {
        $this->registry = $registry;
        $this->freeShippingService = $freeShippingService;
        parent::__construct($context, $data);
    }

    public function isFreeShippingAvailable()
    {
        $product = $this->getProduct();

        if(!$product){
            return false;
        }

        return $this->freeShippingService->isFreeShipped($product);
    }

    public function showTextNoteOnProductsDetailpage(){
        return $this->freeShippingService->showTextNoteOnProductsDetailpage();
    }

    public function showBadgeOnProductsDetailpage(){
        return $this->freeShippingService->showBadgeOnProductsDetailpage();
    }

    private function getProduct()
    {
        $product = $this->registry->registry('product');
        return $product ? $product : false;
    }

}
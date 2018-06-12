<?php

namespace Creativestyle\ProductPositiveIndicatorsExtension\Helper;

class Product extends \Magento\Framework\App\Helper\AbstractHelper
{
    private $config;

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product
     */
    protected $productResource;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var \Creativestyle\ProductPositiveIndicatorsExtension\Service\FreeShippingInterface
     */
    protected $freeShippingService;

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Catalog\Model\ResourceModel\Product $productResource,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfigInterface,
        \Creativestyle\ProductPositiveIndicatorsExtension\Service\FreeShippingInterface $freeShippingService
    ) {
        parent::__construct($context);

        $this->productResource = $productResource;
        $this->storeManager = $storeManager;
        $this->scopeConfig = $scopeConfigInterface;
        $this->freeShippingService = $freeShippingService;
        $this->config = $this->getConfig();
    }

    public function getPopularIconFlag($productId)
    {
        if(!isset($this->config['popular_icon']) or !$this->config['popular_icon']['active']){
            return false;
        }

        return (boolean)$this->productResource->getAttributeRawValue($productId, 'popular_icon', $this->storeManager->getStore()->getId());
    }

    public function isFastShippingEnabled()
    {
        if(!isset($this->config['fast_shipping']) or !$this->config['fast_shipping']['active']){
            return false;
        }

        return true;
    }

    private function getConfig()
    {
        return $this->scopeConfig->getValue('positive_indicators', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    public function isFreeShipped($product)
    {
        return $this->freeShippingService->isFreeShipped($product);
    }

    public function showFreeShippingInProductTiles(){
        return $this->freeShippingService->showInProductTiles();
    }

    public function showFreeShippingTextNoteOnProductsDetailpage(){
        return $this->freeShippingService->showTextNoteOnProductsDetailpage();
    }

    public function showFreeShippingBadgeOnProductsDetailpage(){
        return $this->freeShippingService->showBadgeOnProductsDetailpage();
    }

    public function showFreeShippingInSearchAutosuggest(){
        return $this->freeShippingService->showInSearchAutosuggest();
    }


}

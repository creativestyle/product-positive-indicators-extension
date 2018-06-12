<?php

namespace Creativestyle\ProductPositiveIndicatorsExtension\Block\FastShipping;

class Product extends \Magento\Framework\View\Element\Template
{
    const CACHE_LIFETIME = 300;
    const CACHE_KEY = 'indicator_fast_shipping';

    const XML_PATH_FAST_SHIPPING_GROUP = 'positive_indicators/fast_shipping';

    protected $_template = 'Creativestyle_ProductPositiveIndicatorsExtension::fastshipping/product.phtml';

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var \Magento\Framework\App\CacheInterface
     */
    protected $cache;

    /**
     * @var \Magento\Framework\Serialize\Serializer\Json
     */
    protected $serializer;

    /**
     * @var \Creativestyle\ProductPositiveIndicatorsExtension\Service\DeliveryDataProviderInterface
     */
    protected $deliveryDataProvider;

    public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfigInterface,
        \Magento\Framework\App\CacheInterface $cache,
        \Magento\Framework\Serialize\Serializer\Json $serializer,
        \Creativestyle\ProductPositiveIndicatorsExtension\Service\DeliveryDataProviderInterface $deliveryDataProvider,
        array $data = []
    ) {
        parent::__construct($context, $data);

        $this->scopeConfig = $scopeConfigInterface;
        $this->cache = $cache;
        $this->serializer = $serializer;
        $this->deliveryDataProvider = $deliveryDataProvider;
    }

    public function getDeliveryData()
    {
        $config = $this->getConfig();

        if(!$config['active'] or !$config['delivery_today_time']){
            return false;
        }

        $deliveryData = unserialize($this->cache->load(self::CACHE_KEY));
        $clearCache = $this->getClearCache();

        if($clearCache or!$deliveryData){
            $deliveryData = $this->deliveryDataProvider->prepareDeliveryData($config);

            $this->cache->save(serialize($deliveryData), self::CACHE_KEY, [], self::CACHE_LIFETIME);
        }

        return $this->serializer->serialize($deliveryData);
    }

    private function getConfig()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_FAST_SHIPPING_GROUP,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

}
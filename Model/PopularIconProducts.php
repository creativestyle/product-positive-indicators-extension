<?php

namespace Creativestyle\ProductPositiveIndicatorsExtension\Model;

class PopularIconProducts
{
    private $config;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\Action
     */
    protected $productResourceAction;

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    protected $productCollectionFactory;

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory
     */
    protected $categoryCollectionFactory;

    /**
     * @var \Magento\Catalog\Api\CategoryRepositoryInterface
     */
    protected $categoryRepository;

    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfigInterface,
        \Magento\Catalog\Model\ResourceModel\Product\Action $productResourceAction,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory $categoryCollectionFactory
    )
    {
        $this->scopeConfig = $scopeConfigInterface;
        $this->productResourceAction = $productResourceAction;
        $this->productCollectionFactory = $productCollectionFactory;
        $this->categoryCollectionFactory = $categoryCollectionFactory;

        $this->config = $this->getConfig();
    }

    public function execute()
    {
        $this->removePopularIconFlag();

        if(!$this->config['active']){
            return false;
        }

        $productIds = $this->getProductIds();

        if(empty($productIds)){
            return false;
        }

        $this->addPopularIconFlagToProducts($productIds);
    }

    public function getProductIds()
    {
        $categories = $this->getCategories();

        $productsIds = [];

        if(empty($categories)){
            return $productsIds;
        }

        foreach($categories as $category){
            if($category->getPopularIcon() === '0'){
                continue;
            }

            $productCollection = $category->getProductCollection()
                ->setStore(0)
                ->addFieldToFilter('status', 1);

            if(!$productCollection->getSize()){
                continue;
            }

            $productCollection->setOrder(
                $this->config['sort_by'],
                $this->config['sort_direction']
            );

            $productCollection->setPage(1, (int)$this->config['number_of_products']);

            $products = $productCollection->getItems();

            $productsIds = array_merge($productsIds, array_keys($products));
        }

        return array_unique($productsIds);
    }

    protected function addPopularIconFlagToProducts($productIds)
    {
        foreach($productIds AS $productId){
            $this->productResourceAction->updateAttributes(
                [$productId],
                ['popular_icon' => 1],
                0
            );
        }

        return true;
    }

    protected function removePopularIconFlag()
    {
        $products = $this->getProductsWithFlag();

        if(empty($products)){
            return true;
        }

        foreach($products AS $product){
            $this->productResourceAction->updateAttributes(
                [$product->getId()],
                ['popular_icon' => 0],
                0
            );
        }

        return true;
    }

    private function getProductsWithFlag()
    {
        $collection = $this->productCollectionFactory->create();

        $collection->addAttributeToSelect(['popular_icon']);
        $collection->addFieldToFilter('popular_icon', 1);

        return $collection->getSize() ? $collection->getItems() : [];
    }

    private function getCategories()
    {
        $collection = $this->categoryCollectionFactory->create();
        $collection->addFieldToFilter('is_active', 1);
        $collection->addAttributeToSelect('popular_icon');

        return $collection->getSize() ? $collection->getItems() : [];
    }

    private function getConfig()
    {
        return $this->scopeConfig->getValue('positive_indicators/popular_icon', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }
}
<?php

namespace SoftWebPos\MobileApi\Model\Api;
use Psr\Log\LoggerInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Helper\Image;

class CmsNewProduct implements \SoftWebPos\MobileApi\Api\CmsBestSeller
{
    protected $logger;

    protected $ProductCollection;
    
    protected $productRepository;

    protected $imageHelper;

    protected $storeManager;

    protected $appEmulation;

    protected $response;

    public function __construct(
        LoggerInterface $logger,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $ProductCollection,
        ProductRepositoryInterface $productRepository,
        Image $imageHelper,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Store\Model\App\Emulation $appEmulation,
        \Magento\Framework\Webapi\Rest\Response $response,
        \Magento\CatalogInventory\Api\StockRegistryInterface $stockRegistry
    )
    {
        $this->logger = $logger;
        $this->ProductCollection = $ProductCollection;
        $this->productRepository = $productRepository;
        $this->imageHelper = $imageHelper;
        $this->storeManager = $storeManager;
        $this->appEmulation = $appEmulation;
        $this->response = $response;
        $this->stockRegistry = $stockRegistry;
    }
 
    /**
     * @inheritdoc
     */
    public function getApiData()
    {
        $response = ['success' => false];
        try {
            $collection = $this->ProductCollection->create();
            $collection->addAttributeToSelect('entity_id')->addAttributeToSort('created_at', 'desc');
            $collection->addFieldToFilter('visibility', array('eq' => \Magento\Catalog\Model\Product\Visibility::VISIBILITY_BOTH));
            $collection->setPageSize(20);
            $data = $collection->getData();
            $storeId = $this->storeManager->getStore()->getId();
            $this->appEmulation->startEnvironmentEmulation($storeId, \Magento\Framework\App\Area::AREA_FRONTEND, true);
            foreach($data as $key => $product){
                $productdata = $this->getLoadProduct($product['entity_id']);
                $responsedata[$key]['id'] = $productdata->getId();
                $responsedata[$key]['sku'] = $productdata->getSku();
                $responsedata[$key]['name'] = $productdata->getName();
                $responsedata[$key]['price'] = $productdata->getFinalPrice();
                $responsedata[$key]['image'] = $this->imageHelper->init($productdata, 'product_base_image')->getUrl();
                $responsedata[$key]['Isinstock'] = $this->getStockStatus($productdata);
            }
            $this->appEmulation->stopEnvironmentEmulation();
            
            $response = $responsedata;
        } catch (\Exception $e) {
            $response = ['success' => false, 'message' => $e->getMessage()];
            $this->logger->info($e->getMessage());
        }
        return $this->response->setHeader('Content-Type', 'application/json', true)->setBody(json_encode($response))->sendResponse();
    }

    public function getLoadProduct($id) {
        return $this->productRepository->getById($id);
    }

    public function getStockStatus($product)
    {
        $stockItem = $this->stockRegistry->getStockItem($product->getId());
        $stockData = $stockItem->getData();
        return $stockItem->getIsInStock();
    }
}

<?php

namespace SoftWebPos\MobileApi\Plugin;

use Magento\Sales\Api\Data\OrderExtensionFactory;
use Magento\Sales\Api\Data\OrderExtensionInterface;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\Data\OrderSearchResultInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Catalog\Api\ProductRepositoryInterfaceFactory as ProductRepository;
use Magento\Catalog\Helper\ImageFactory as ProductImageHelper;
use Magento\Store\Model\StoreManagerInterface as StoreManager;
use Magento\Store\Model\App\Emulation as AppEmulation;

class OrderRepositoryPlugin
{
    protected $extensionFactory;

    protected $productRepository;

    protected $productImageHelper;

    protected $storeManager;

    protected $appEmulation;


    public function __construct(
        OrderExtensionFactory $extensionFactory,
        ProductRepository $productRepository,
        ProductImageHelper $productImageHelper,
        AppEmulation $appEmulation,
        StoreManager $storeManager


    )
    {
        $this->extensionFactory = $extensionFactory;
        $this->productRepository = $productRepository;
        $this->productImageHelper = $productImageHelper;
        $this->appEmulation = $appEmulation;
        $this->storeManager = $storeManager;

    }

    // public function afterGet(OrderRepositoryInterface $subject, OrderInterface $order)
    // {
    //     $customerFeedback = "test";//$order->getData(self::FIELD_NAME);
    //     $extensionAttributes = $order->getExtensionAttributes();
    //     $extensionAttributes = $extensionAttributes ? $extensionAttributes : $this->extensionFactory->create();
    //     $extensionAttributes->setCustomerFeedback($customerFeedback);
    //     $order->setExtensionAttributes($extensionAttributes);

    //     return $order;
    // }

    public function afterGetList(OrderRepositoryInterface $subject, OrderSearchResultInterface $searchResult)
    {
        $orders = $searchResult->getItems();
       
        foreach ($orders as &$order) {

             //get All product of order
            foreach ($order->getAllItems() as $item) { 
                $product = $this->productRepository->create()->getById($item->getProductId());
                $imageurl = $this->getImageUrl($product, 'product_thumbnail_image');
               // echo"<pre>";print_r($item->getExtensionAttributes());
            }         
           // exit;
            $customerFeedback = $imageurl;
            $extensionAttributes = $order->getExtensionAttributes();
            $extensionAttributes = $extensionAttributes ? $extensionAttributes : $this->extensionFactory->create();
            $extensionAttributes->setImageUrl($customerFeedback);
            $order->setExtensionAttributes($extensionAttributes);
        }

        return $searchResult;
    }

    protected function getImageUrl($product, string $imageType = '')
    {
        $storeId = $this->storeManager->getStore()->getId();
        $this->appEmulation->startEnvironmentEmulation($storeId, \Magento\Framework\App\Area::AREA_FRONTEND, true);
        $imageUrl = $imageUrl = $this->productImageHelper->create()->init($product, $imageType)->getUrl();
        $this->appEmulation->stopEnvironmentEmulation();
        return $imageUrl;
    }
}
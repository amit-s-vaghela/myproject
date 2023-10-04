<?php

namespace SoftWebPos\MobileApi\Plugin;

use Magento\Catalog\Model\ProductFactory;
use Magento\Sales\Api\Data\OrderItemInterface;
use Magento\Sales\Api\OrderItemRepositoryInterface;
use Magento\Sales\Api\Data\OrderItemExtensionFactory;
use Magento\Sales\Api\Data\OrderItemSearchResultInterface;

class AddCustomProductAttribute
{

    protected $orderItemExtensionFactory;

    protected $productFactory;

    public function __construct(
        OrderItemExtensionFactory $orderItemExtensionFactory,
        ProductFactory $productFactory
    ) {
        $this->orderItemExtensionFactory = $orderItemExtensionFactory;
        $this->productFactory = $productFactory;
    }
     
    public function afterGet(OrderItemRepositoryInterface $subject, OrderItemInterface $orderItem)
    {
        $customAttribute = $orderItem->getData('my_custom_product_attribute');
 
        $extensionAttributes = $orderItem->getExtensionAttributes();
        $extensionAttributes = $extensionAttributes ? $extensionAttributes : $this->extensionFactory->create();

         $extensionAttributes->setMyCustomProductAttribute($customAttribute);
        $orderItem->setExtensionAttributes($extensionAttributes);

        return $orderItem;
    }

    public function afterGetList(OrderItemRepositoryInterface $subject, OrderItemSearchResultInterface $searchResult)
    {
        $orders = $searchResult->getItems();

        foreach ($orders as &$item) {

            $extensionAttributes = $item->getExtensionAttributes();
            $extensionAttributes = $extensionAttributes ? $extensionAttributes : $this->extensionFactory->create();
            $extensionAttributes->setMyCustomProductAttribute("test");
            $item->setExtensionAttributes($extensionAttributes);
        }

        return $searchResult;
    }
	
}
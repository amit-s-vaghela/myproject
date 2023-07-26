<?php
namespace Devstree\InventoryAudit\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $_customerSession;
    /**
     * @var \Magento\Catalog\Model\ProductFactory
     */
    private $productFactory;

    /**
     * Data constructor.
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Magento\Customer\Model\SessionFactory $customerSession
     * @param \Magento\Catalog\Model\ProductFactory $productFactory
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Customer\Model\SessionFactory $customerSession,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        \Magento\Eav\Api\AttributeSetRepositoryInterface $attributeSet,
        \Magento\InventorySalesAdminUi\Model\GetSalableQuantityDataBySku $StockState
    ) {
        $this->_customerSession = $customerSession->create();
        $this->productFactory = $productFactory;
        $this->productRepository = $productRepository;
        $this->attributeSet = $attributeSet;
        $this->StockState = $StockState;

        parent::__construct($context);
    }

   
 
    /**
     * Get product inquiry enabled or not from system configuration
     *
     * @return mixed
     */
    public function getInquiryEnabledGlobally()
    {
        return $this->getConfig('product_inquiry/general/enable');
    }

    /**
     * Get product inquiry title from system configuration
     *
     * @return mixed
     */
    public function getProductBySku($sku ,$item)
    {
        $productData = [];
        $phycial_qty = $item;
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

        $product =  $this->productRepository->get($sku);
       // echo "<pre>";print_r($product->getData());
        $productData["qty"] = $product['quantity_and_stock_status']['qty'];
       // $StockState = $objectManager->get('\Magento\InventorySalesAdminUi\Model\GetSalableQuantityDataBySku');
        $StockState = $this->StockState;
        $qty = $StockState->execute($product->getSku());
        $attributeSetRepository = $this->attributeSet->get($product->getAttributeSetId());
        $attributeSetName = $attributeSetRepository->getAttributeSetName();
        $salable_qty = $qty[0]['qty'];
        $priceFormat = round($product->getPrice(), 2);
        $productData["name"] = $product->getName();
        $productData["price"] = $priceFormat;
        $productData["salable_qty"] = $salable_qty;
        $productData["attribute_set_id"] = $attributeSetName;
        $productData["gst"] = 18;
        $qty = $productData["qty"];
        $difference = $this->getDifference($qty , $phycial_qty);
        $productData["difference"] = $difference;
        $price = $productData["price"];
        $gst = $productData["gst"];
        $difference = $this->getValue($price, $gst, $difference);
        $productData["value"] = $difference;
        //echo "<pre>";print_r($productData);exit;
        return $productData;
    }
    public function getDifference($qty , $phycial_qty) {

       return $difference = $qty - $phycial_qty;
    } 
    public function getValue($price, $gst, $difference) {
        // echo "price ".$price; 
        // echo "<br>";
        // echo "gst   ".$gst;
        // echo "<br>";

        // echo "diff  ".$difference;
        // echo "<br>";

        $percentage = $gst;
         $percentage = ($percentage / 100); 
         $percentage = 1 + $percentage;
        return $value = $price * $percentage * $difference;
     }   
}

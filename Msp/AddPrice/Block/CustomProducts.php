<?php
namespace Msp\AddPrice\Block;

class CustomProducts extends \Magento\Framework\View\Element\Template
{    
    protected $_productCollectionFactory;
        
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,        
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Framework\Url\Helper\Data $urlHelper,        
        array $data = []
    )
    {    
        $this->_productCollectionFactory = $productCollectionFactory; 
        $this->urlHelper = $urlHelper;   
        parent::__construct($context, $data);
    }
    
    public function getProductCollection()
    {
        $collection = $this->_productCollectionFactory->create();
        $collection->addAttributeToSelect('*');
        $collection->addFieldToFilter('custom_yes_no', ['in' => 1]);

        $collection->setPageSize(5); // fetching only 3 products
        //echo "<pre>";print_r($collection->getData());exit;
        return $collection;
    }
    public function getAddToCartPostParams(\Magento\Catalog\Model\Product $product)
    {
        $url = $this->getAddToCartUrl($product);
        return [
            'action' => $url,
            'data' => [
                'product' => $product->getEntityId(),
                \Magento\Framework\App\ActionInterface::PARAM_NAME_URL_ENCODED =>
                    $this->urlHelper->getEncodedUrl($url),
            ]
        ];
    }
}
?>
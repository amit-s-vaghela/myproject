<?php
namespace Dolphin\Job\Block\Index;
 
use Magento\Framework\View\Element\Template;
 
class View extends Template
{
  public function __construct(
    Template\Context $context, 
    \Dolphin\Job\Model\GridFactory $gridFactory,
    \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $_collection,
    array $data = []
  )
  {
        parent::__construct($context, $data);
        $this->gridFactory = $gridFactory;
        $this->_collection = $_collection;
  }
 
  // protected function _prepareLayout()
  //   {
  //       return parent::_prepareLayout();
  //   }
 
  public function getRandomProduct()
  {
    $collection = $this->_collection->create();
    $collection->addAttributeToSelect('*')->getSelect()->orderRand()->limit(10);
    return $collection->setPageSize(1);
  }

  public function getAllRegisterData()
  {
    $collection = $this->gridFactory->create();
    $collection = $collection->getCollection();
    //echo "<pre>";print_r($collection->getData());exit();
    return $collection;
  }
}
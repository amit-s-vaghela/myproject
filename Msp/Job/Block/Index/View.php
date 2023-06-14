<?php
namespace Msp\Job\Block\Index;
 
use Magento\Framework\View\Element\Template;
 
class View extends Template
{
  public function __construct(
    Template\Context $context, 
    \Msp\Job\Model\GridFactory $gridFactory,
    array $data = []
  )
  {
        parent::__construct($context, $data);
        $this->gridFactory = $gridFactory;
  }
 
  // protected function _prepareLayout()
  //   {
  //       return parent::_prepareLayout();
  //   }
 
  public function getAge($date)
  {
    // echo "string".$date;exit();
    $bday = new DateTime('11.4.1987'); // Your date of birth
    $today = new Datetime(date('m.d.y'));
    $diff = $today->diff($bday);
    return $diff->y;
  }

  public function getAllRegisterData()
  {
    $collection = $this->gridFactory->create();
    $collection = $collection->getCollection();
    //echo "<pre>";print_r($collection->getData());exit();
    return $collection;
  }
}
<?php 
namespace Dolphin\Job\Model;

class Grid extends \Magento\Framework\Model\AbstractModel 
{ 
    protected function _construct()
    {
        $this->_init('Dolphin\Job\Model\ResourceModel\Grid');
    }
   
}

<?php 
namespace Msp\Job\Model;

class Grid extends \Magento\Framework\Model\AbstractModel 
{ 
    protected function _construct()
    {
        $this->_init('Msp\Job\Model\ResourceModel\Grid');
    }
   
}

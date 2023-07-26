<?php
namespace Devstree\InventoryAudit\Model;

class Grid extends \Magento\Framework\Model\AbstractModel
{
    protected function _construct()
    {
        $this->_init('Devstree\InventoryAudit\Model\ResourceModel\Grid');
    }
    
}

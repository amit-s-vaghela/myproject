<?php
namespace Devstree\InventoryAudit\Model\ResourceModel\Grid;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected $_idFieldName = 'inventory_audit_id';
    
    protected function _construct()
    {
        $this->_init(
            'Devstree\InventoryAudit\Model\Grid',
            'Devstree\InventoryAudit\Model\ResourceModel\Grid'
        );
    }
}

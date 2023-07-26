<?php
namespace Devstree\InventoryAudit\Model\ResourceModel;

class Grid extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    /**
     * Initialize resource model.
     */
    protected function _construct()
    {
        $this->_init('inventory_audit_data', 'inventory_audit_id');
    }
}

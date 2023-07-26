<?php
namespace Devstree\InventoryAudit\Block\Adminhtml\Grid;
/**
 * Class DisplayInfo
  */
class DisplayInfo extends \Magento\Backend\Block\Template
{

    public function getFormAction()
    {
        return $this->getUrl('inventoryaudit/product/scan', ['_secure' => true]);
    }
}
<?php
namespace Devstree\InventoryAudit\Controller\Adminhtml\Grid;

use Magento\Framework\Controller\ResultFactory;

class AddRow extends \Magento\Backend\App\Action
{
      
    /**
     * @param \Magento\Backend\App\Action\Context $context
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry    
        ) {
        parent::__construct($context);
    }

    /**
     * Mapped Grid List page.
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {

        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $title =  __('Inventory Form');
        $resultPage->setActiveMenu('Devstree_InventoryAudit::add_row');
        $resultPage->getConfig()->getTitle()->prepend($title);
        return $resultPage;
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Devstree_InventoryAudit::add_row');
    }
}

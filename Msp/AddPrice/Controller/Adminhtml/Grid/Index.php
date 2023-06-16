<?php 
namespace Msp\AddPrice\Controller\Adminhtml\Grid; 

class Index extends \Magento\Backend\App\Action
{
   
    private $resultPageFactory;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }


    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Msp_AddPrice::manager');
        $resultPage->getConfig()->getTitle()->prepend(__('Customer Grid List'));
        return $resultPage;
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Msp_AddPrice::manager');
    }
}

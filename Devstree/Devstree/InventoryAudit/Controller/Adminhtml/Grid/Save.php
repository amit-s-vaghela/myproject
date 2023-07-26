<?php
namespace Devstree\InventoryAudit\Controller\Adminhtml\Grid;

use Magento\Framework\App\Filesystem\DirectoryList;

class Save extends \Magento\Backend\App\Action
{
    public function __construct(
            \Magento\Backend\App\Action\Context $context,
            \Magento\MediaStorage\Model\File\UploaderFactory $uploaderFactory,
            \Magento\Framework\Image\AdapterFactory $adapterFactory,
            \Magento\Framework\Filesystem $filesystem,
            \Devstree\InventoryAudit\Model\GridFactory $gridFactory,
            \Magento\Framework\Session\SessionManagerInterface $session
    ) {
            $this->uploaderFactory = $uploaderFactory;
            $this->adapterFactory = $adapterFactory;
            $this->filesystem = $filesystem;
            parent::__construct($context);
            $this->gridFactory = $gridFactory;
            $this->session = $session;
    }

    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        $getSessionData = $this->getSessionData();
        //echo "<pre>SESSION ";print_r($getSessionData);

        $differenceTotal = 0;
        foreach($getSessionData as $key=>$value) {
            $differenceTotal+= $value['difference'];
        }
        if ($differenceTotal > 0) {
            $data['positive_difference_value'] = $differenceTotal;
            $data['negative_difference_value'] = "-";
        } else {
            $data['negative_difference_value'] = $differenceTotal; 
            $data['positive_difference_value'] = "-";

        }
        //echo "<pre>";print_r($data);exit;
        try {
           
            $rowData = $this->gridFactory->create();
            $rowData->setData($data);
            $rowData->save();
            $this->messageManager->addSuccess(__('Barcode data has been successfully saved.'));

        } catch (\Exception $e) {
            $this->messageManager->addError(__($e->getMessage()));
        }
        $this->_redirect('inventoryaudit/grid/index');
        
    }
   
    public function getSessionData(){
        $this->session->start();
        return $this->session->getSessionData();
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Devstree_InventoryAudit::save');
    }
}


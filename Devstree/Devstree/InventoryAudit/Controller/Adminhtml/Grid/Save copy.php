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

        
        // $pdffile = $this->getRequest()->getFiles('pdffile');
        // $pdffile = $this->getRequest()->getFiles('csvfile');

        // if(isset($_FILES['pdffile']['name']) && $_FILES['pdffile']['name'] != '') {
        //     try{
        //         $uploaderFactory = $this->uploaderFactory->create(['fileId' => 'pdffile']);
        //         $uploaderFactory->setAllowedExtensions(['pdf']);
        //         $uploaderFactory->setAllowRenameFiles(true);
        //         // $uploaderFactory->setFilesDispersion(true);
        //         $mediaDirectory = $this->filesystem->getDirectoryRead(DirectoryList::MEDIA);
        //         $destinationPath = $mediaDirectory->getAbsolutePath('pdf/');
        //         $folderPath = $destinationPath;
        //         $result = $uploaderFactory->save($folderPath);
               
        //         $pdfPath = 'pdf'.'/'.$result['file'];
        //         $data['pdf'] = $pdfPath;
        //        // echo "image Path ==>  ".$pdfPath;exit;
               

        //     } catch (\Exception $e) {
        //         $message = $e->getMessage();
        //         $this->_getSession()->addError($message);
        //     }
        // }
        // if(isset($_FILES['csvfile']['name']) && $_FILES['csvfile']['name'] != '') {
        //     try{
        //         $uploaderFactory = $this->uploaderFactory->create(['fileId' => 'csvfile']);
        //         $uploaderFactory->setAllowedExtensions(['csv']);
        //         $uploaderFactory->setAllowRenameFiles(true);
        //         // $uploaderFactory->setFilesDispersion(true);
        //         $mediaDirectory = $this->filesystem->getDirectoryRead(DirectoryList::MEDIA);
        //         $destinationPath = $mediaDirectory->getAbsolutePath('csv/');
        //         $folderPath = $destinationPath;
        //         $result = $uploaderFactory->save($folderPath);
               
        //         $pdfPath = 'csv'.'/'.$result['file'];
        //         $data['csv'] = $pdfPath;  
        //         $data['positive_difference_value'] = $pdfPath;
        //         $data['negative_difference_value'] = $pdfPath;             
             
        //     } catch (\Exception $e) {
        //         $message = $e->getMessage();
        //         $this->_getSession()->addError($message);
        //     }
        // }
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

    // /**
    //  * @return bool
    //  */
    // protected function _isAllowed()
    // {
    //     return $this->_authorization->isAllowed('Webkul_Grid::save');
    // }
}


<?php
namespace Devstree\InventoryAudit\Controller\Adminhtml\Grid;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\App\Filesystem\DirectoryList;

class Export extends \Magento\Backend\App\Action
{
      
    /**
     * @param \Magento\Backend\App\Action\Context $context
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        PageFactory $resultPageFactory,
        \Magento\Framework\App\Response\Http\FileFactory $fileFactory,
        \Magento\Framework\Filesystem $filesystem
        ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->resultFactory = $resultJsonFactory;
        $this->_fileFactory = $fileFactory;
        $this->directory = $filesystem->getDirectoryWrite(DirectoryList::VAR_DIR);

    }

    /**
     * Mapped Grid List page.
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        $fileName = $this->getRequest()->getParam('fileName');
        if($fileName ) {
            $name = date('m_d_Y_H_i_s');
            // $filepath = 'export/custom05_09_2023_06_41_39.csv';
            $filepath = 'csv/' . $fileName;
            
            $content = [];
            $content['type'] = 'filename'; // must keep filename
            $content['value'] = $filepath;
            $content['rm'] = '0'; //remove csv from var folder

            $csvfilename = $fileName;//'Product.csv';
            return $this->_fileFactory->create($csvfilename, $content, DirectoryList::VAR_DIR); 
        }      

    }
    
}

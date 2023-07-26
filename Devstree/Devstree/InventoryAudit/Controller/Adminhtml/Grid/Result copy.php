<?php
namespace Devstree\InventoryAudit\Controller\Adminhtml\Grid;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\App\Filesystem\DirectoryList;

class Result extends \Magento\Backend\App\Action
{
    protected $registry = null;

      
    /**
     * @param \Magento\Backend\App\Action\Context $context
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        PageFactory $resultPageFactory,
        \Magento\Framework\App\Response\Http\FileFactory $fileFactory,
        \Magento\Framework\Filesystem $filesystem,
        \Magento\Framework\Session\SessionManagerInterface $session
        ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->resultFactory = $resultJsonFactory;
        $this->_fileFactory = $fileFactory;
        $this->directory = $filesystem->getDirectoryWrite(DirectoryList::VAR_DIR);
        $this->session = $session;

    }

    /**
     * Mapped Grid List page.
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        $resultJson = $this->resultFactory->create();
        $resultPage = $this->resultPageFactory->create();
        $data = $this->getRequest()->getParams();
        $barcode_data = $data["barcode_data"];
        $brand = $data["brand"];
        if($data) {
             $fileName = $this->createCSVdata($data);
             if(!empty($fileName)) {
                $data["filename"] = $fileName;
             }
        }
        

        //echo"<pre> data";print_r($data);exit;
        $block = $resultPage->getLayout()
                ->createBlock('Devstree\InventoryAudit\Block\Adminhtml\View')
                ->setTemplate('Devstree_InventoryAudit::view.phtml')
                ->setData('data',$data)
                ->toHtml();
        //echo $block;exit;
       // echo "<pre>";var_dump($block);exit;
        $this->setValue($block);
        $resultJson->setData(['output' => $block]);
        return $resultJson;

    }
    public function createCSVdata($data)
    {
        $barcodeArray = explode(';', $data["barcode_data"]);
        $barcodeArray = array_map('trim', $barcodeArray);
        $barcodeArray = array_count_values($barcodeArray);
       // echo"<pre> createCSV";print_r($barcodeArray);exit;
        $name = date('m_d_Y_H_i_s');
        $fileName = 'custom' . $name . '.csv';
        $filepath = 'csv/custom' . $name . '.csv';
        $this->directory->create('csv');
        $stream = $this->directory->openFile($filepath, 'w+');
        $stream->lock();
        $header = ['SKU','PHYSICALSTOCK'];
        $stream->writeCsv($header);
       // echo "<pre>";var_dump($header);exit;
        foreach($barcodeArray as $key => $item) {

            $itemData = [];
            $itemData[] = $key;
            $itemData[] = $item;
            $stream->writeCsv($itemData);
        }
        return $fileName;
        
    }
    public function setValue($value){
        $this->session->start();
        $this->session->setMessage($value);
     }
}

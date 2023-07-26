<?php
namespace Devstree\InventoryAudit\Controller\Adminhtml\Grid;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\App\Filesystem\DirectoryList;
require_once('../lib/internal/TCPDF/TCPDF.php');

class Result extends \Magento\Backend\App\Action
{
    protected $_registry;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        PageFactory $resultPageFactory,
        \Magento\Framework\App\Response\Http\FileFactory $fileFactory,
        \Magento\Framework\Filesystem $filesystem,
        \Magento\Framework\App\Filesystem\DirectoryList $dir,
        \Devstree\InventoryAudit\Helper\Data $helper,
        \Magento\Framework\Session\SessionManagerInterface $session,
        \Magento\Framework\Registry $registry
        ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->resultFactory = $resultJsonFactory;
        $this->_fileFactory = $fileFactory;
        $this->directory = $filesystem->getDirectoryWrite(DirectoryList::VAR_DIR);
        $this->_dir = $dir;
        $this->helper = $helper;
        $this->session = $session;
        $this->_registry = $registry;

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
                $data["csv_file"] = $fileName;
                //pdf 
                $blockHtml = $resultPage->getLayout()
                ->createBlock('Devstree\InventoryAudit\Block\Adminhtml\View')
                ->setTemplate('Devstree_InventoryAudit::pdf_html_view.phtml')
                ->setData('data',$data)
                ->toHtml();
                $html = $blockHtml;
                $pdf_file = $this->createPDFdata($html);
                $data["pdf_file"] =  $pdf_file;
             }
            
        }

        //echo"<pre> data";print_r($data);exit;
        $block = $resultPage->getLayout()
                ->createBlock('Devstree\InventoryAudit\Block\Adminhtml\View')
                ->setTemplate('Devstree_InventoryAudit::view.phtml')
                ->setData('data',$data)
                ->toHtml();

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
        $header = ['sku','physicalstock','attribute_set_code','name','qty','salable_qty','cost','gst','Difference','Value'];
        $stream->writeCsv($header);
       // echo "<pre>";var_dump($header);exit;
       $dataProcess = [];
        foreach($barcodeArray as $key => $item) {
            $sku = $key;
            $product = $this->helper->getProductBySku($sku ,$item);
            //echo "<pre>";print_r($product);exit;
            $data = [];
            $itemData = [];
            $itemData[] = $key;
            $itemData[] = $item;

            $itemData[] = $product['attribute_set_id'];
            $itemData[] = $product['name'];
            $itemData[] = $product['qty'];
            $itemData[] = $product['salable_qty'];
            $itemData[] = $product['price'];
            $itemData[] = $product['gst'].'%';
            $itemData[] = $product['difference'];
            $itemData[] = $product['value'];
            $stream->writeCsv($itemData);

            $data['sku'] = $key;
            $data['phy_qty'] = $item;
            $data['attribute_set_id'] = $product['attribute_set_id'];
            $data['name'] = $product['name'];
            $data['qty'] = $product['qty'];
            $data['salable_qty'] = $product['salable_qty'];
            $data['price'] = $product['price'];
            $data['gst'] = $product['gst'].'%';
            $data['difference'] = $product['difference'];
            $data['value'] = $product['value'];
            $dataProcess[] =$data;
        }
        //echo "<pre>DATA";print_r($dataProcess);exit;
        $this->setSessionData($dataProcess);
        $this->_registry->register('dataProcess',$dataProcess);

        return $fileName;
        
    }
    public function createPDFdata($html)
    {
        $tcpdf = new \TCPDF_TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $tcpdf->SetCreator(PDF_CREATOR);
        $tcpdf->SetTitle('Title');
        $tcpdf->setPrintHeader(false);
        $tcpdf->setPrintFooter(false);
        $tcpdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        $tcpdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $tcpdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $tcpdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        $tcpdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $tcpdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
            require_once(dirname(__FILE__) . '/lang/eng.php');
            $tcpdf->setLanguageArray($l);
        }
        $tcpdf->setFontSubsetting(true);
        //$html=$this->getHtml();
        // set some language dependent data:
        $lg = Array();
        $lg['a_meta_charset'] = 'UTF-8';
        $tcpdf->setLanguageArray($lg);
        // set font
        $tcpdf->SetFont('freesans', '', 11);
        // remove default header/footer
        //$tcpdf->setPrintHeader(false);
        $tcpdf->setPrintFooter(false);
        $tcpdf->AddPage();
        $tcpdf->writeHTML($html, true, false, true, false, '');
        $tcpdf->lastPage();
        //$tcpdf->Output('report_per_route.pdf', 'I');
        //$this->logger->debug('report_per_route');
        $this->directory->create('pdf');
        $path = $this->_dir->getPath('var');

        $baseurl = $this->getUrl();
        $filename = 'Sample_pdf'. time().'.pdf';
        $filePath = $path .'/pdf/'. $filename;
        $tcpdf->Output($filePath, 'F');
        //return $tcpdf->Output("report_per_route.pdf", 'D');
        return $filename;
        
    }
    public function setSessionData($data){
        $this->session->start();
        $this->session->setSessionData($data);
     }
}

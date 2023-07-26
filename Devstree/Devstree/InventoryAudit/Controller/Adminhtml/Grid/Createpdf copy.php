<?php
namespace Devstree\InventoryAudit\Controller\Adminhtml\Grid;

use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Filesystem\DirectoryList;
require_once('lib/internal/TCPDF/TCPDF.php');

class Createpdf extends \Magento\Backend\App\Action
{
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\App\Filesystem\DirectoryList $dir,
        ) {
        parent::__construct($context);
        $this->_dir = $dir;
        

    }
    // public function execute()
    // {
    //     $fileName = $this->getRequest()->getParam('fileName');
    //     if($fileName) {
    //        echo"called";exit;
    //     } 
    // }
    public function execute()
    {
        $tcpdf = new \TCPDF_TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $tcpdf->SetCreator(PDF_CREATOR);
        $tcpdf->SetTitle('Title');
        $tcpdf->setPrintHeader(false);
        $tcpdf->setPrintFooter(false);
        // set default monospaced font
        $tcpdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        // set margins
        $tcpdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $tcpdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $tcpdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        // set auto page breaks
        $tcpdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        // set image scale factor
        $tcpdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        // set some language-dependent strings (optional)
        if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
            require_once(dirname(__FILE__) . '/lang/eng.php');
            $tcpdf->setLanguageArray($l);
        }
        // set default font subsetting mode
        $tcpdf->setFontSubsetting(true);
        //your htmls here
        $html=$this->getHtml();
        // set some language dependent data:
        $lg = Array();
        $lg['a_meta_charset'] = 'UTF-8';
        $tcpdf->setLanguageArray($lg);
        // set font
        //dejavusans & freesans For Indian Rupees symbol
        $tcpdf->SetFont('freesans', '', 12);
        // remove default header/footer
        //$tcpdf->setPrintHeader(false);
        $tcpdf->setPrintFooter(false);
        $tcpdf->AddPage();
        $tcpdf->writeHTML($html, true, false, true, false, '');
        $tcpdf->lastPage();
        //$tcpdf->Output('report_per_route.pdf', 'I');
        //$this->logger->debug('report_per_route');
        $baseurl = $this->getDirPath();
        $filename = $baseurl .'/export/'. 'Sample_pdf'. time().'.pdf';
        $tcpdf->Output($filename, 'I');
    }
    
}
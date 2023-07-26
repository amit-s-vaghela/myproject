<?php
namespace Devstree\InventoryAudit\Block\Adminhtml;
use Magento\Backend\Block\Template;
class View extends Template
{
    public function __construct(
        Template\Context $context, 
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\Form\FormKey $formKey,
        array $data = []
    )
    {
        $this->_registry = $registry;
        $this->formKey = $formKey;

        parent::__construct($context, $data);
    }

    public function getProcessData($data)
    {
        return $this->_registry->registry('dataProcess');
    }
    public function getBarcodeData($data)
    {
        //echo"<pre>";print_r($data);
        if($data["barcode_data"]) {
            $barcodeArray = explode(';', $data["barcode_data"]);
            $barcodeArray = array_map('trim', $barcodeArray);
            $barcodeArray = array_count_values($barcodeArray);
            //echo"<pre>";print_r($barcodeArray);
            return $barcodeArray;
        }
       
    }
    public function getFormkey()
    {
        return $this->formKey->getFormKey();
    }

}
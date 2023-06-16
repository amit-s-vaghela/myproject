<?php
namespace Msp\CustomerPic\Block;

use Magento\Framework\View\Element\Template\Context;
use Magento\Customer\Model\SessionFactory;
use Magento\Customer\Model\Customer;

class Link extends \Magento\Framework\View\Element\Html\Link
{

  protected $session;

  public function __construct(
      Context $context,
      SessionFactory $session,
      Customer $customer,
      \Magento\Store\Model\StoreManagerInterface $storeManager,
      \Magento\Framework\View\Asset\Repository $assetRepo,
      array $data = []
    ){
    $this->session = $session;
    $this->customer = $customer;
    $this->_storeManager = $storeManager;
    $this->_assetRepo = $assetRepo;
    parent::__construct($context, $data);
  }
  /**
   * Render block HTML.
   *
   * @return string
  */
  protected function _toHtml() {
    if (false != $this->getTemplate()) {
      return parent::_toHtml();
    }
    $width = 35;

    $src ="";
    $customer = $this->session->create();
    if($customer_id = $customer->getCustomer()->getId()) {
      
      $customer = $this->customer->load($customer_id);
      $filePath = $customer->getProfilePic();
      //echo "<pre>";print_r($customer->getProfilePic());
      $baseUrl = $this->_storeManager->getStore()->getBaseUrl();
      if(!empty($filePath)){
        $src = $baseUrl . 'media/customer' . $filePath;
        // return '<li><img src="'.$src.'" width="' . $width . 'px" height="100px"/></li>';
      }else {
        $src = $this->_assetRepo->getUrl("Msp_CustomerPic::images/default.jpg");
      } 
      return '<li><img src="'.$src.'" width="' . $width . 'px" height="100px" style="margin-top: 5px;"/></li>';    
    } else { 
      return '<li></li>'; 
    }

    // else {
    //   $src = $this->_assetRepo->getUrl("Msp_CustomerPic::images/default.jpg");

    // }
      
    

    

    // $label = $this->escapeHtml($this->getLabel());
    // return '<li><a ' . $this->getLinkAttributes() . ' >' . "test" . '</a></li>';

  }
}
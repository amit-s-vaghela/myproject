<?php
namespace Msp\CustomerPic\Block\Customer\Account; 

use \Magento\Framework\View\Element\Template;
use \Magento\Framework\View\Element\Template\Context;
use \Magento\Customer\Model\Session;
use \Magento\Customer\Model\Customer;

class ProfilePic extends Template
{
	protected $session;

	public function __construct(
			Context $context,
			Session $session,
			Customer $customer,
			\Magento\Store\Model\StoreManagerInterface $storeManager,
			array $data = []
		){
		$this->session = $session;
		$this->customer = $customer;
		$this->_storeManager = $storeManager;
		parent::__construct($context, $data);
	}

	public function getCustomerId()
	{
		$customer_id = $this->session->getCustomer()->getId();
		return $customer_id;
	}
	
	public function getCustomer() {
		$customer_id = $this->session->getCustomer()->getId();
		$customer = $this->customer->load($customer_id);
		return $customer;
	}
	
	
	public function getProfilePic() {
		
		//echo "<pre> test";print_r($this->getCustomer()->getData());exit();
		$customerData = $this->getCustomer();
		$url = $customerData->getData('profile_pic');
		if (!empty($url)) {
			$url = $this->getCustomerImageUrl($url);
		}
		return $url; 
	}

	public function getCustomerImageUrl($filePath) {
		
		$BaseUrl = $this->_storeManager->getStore()->getBaseUrl();
		return $BaseUrl . 'media/customer' . $filePath;
	}
}
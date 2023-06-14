<?php
namespace Msp\Job\Controller\Api;

////http://milandev.me/magento-2-how-to-get-external-api-value-in-the-ui-component-form-by-adding-a-custom-html-button/

use Magento\Framework\Controller\ResultFactory;

class Post extends \Magento\Framework\App\Action\Action
{

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    /**
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory resultPageFactory
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Controller\Result\RedirectFactory $resultRedirectFactory,
        \Magento\Customer\Model\SessionFactory $customerSession,
         \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Psr\Log\LoggerInterface $logger

    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->resultRedirectFactory = $resultRedirectFactory;
        $this->customerSession = $customerSession;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->logger = $logger;
        parent::__construct($context);
    }
    
    public function execute()
    {


        try {

	        	$result = $this->resultJsonFactory->create();
	            $resultPage = $this->resultPageFactory->create();

	        	$data = '{
				"data":{
				"name":"Amit", 
				"mobile":9898749414}
				}';

				$adminToken = array(
				'Authorization: Bearer eyJraWQiOiIxIiwiYWxnIjoiSFMyNTYifQ.eyJ1aWQiOjEsInV0eXBpZCI6MiwiaWF0IjoxNjc1NjgzNTA1LCJleHAiOjE2NzU2ODcxMDV9.jmCpPhACTXxRCAwa_icF_UjgoLMdWGdGitYnSU5oJqE',
				'Content-Type: application/json'
				);
				$service_url = 'http://202.131.107.107:8013/a_magento244/pub/rest/V1/custom-api/test/';
	            $curl = curl_init();

				curl_setopt_array($curl, array(
				CURLOPT_URL => $service_url,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => '',
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 0,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => 'POST',
				CURLOPT_POSTFIELDS => $data,
				CURLOPT_HTTPHEADER => $adminToken,
				));

				$response = curl_exec($curl);
				curl_close($curl);
				$response = json_decode($response);
				//$result->setData(['data' => $response]);
				/*$data = [
				    'status' => true,
				    'message' => 'We will let you know!'
				];*/
				$result->setData($response[0]);
                return $result;
             
             
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            return $this->messageManager->addError($e->getMessage());
        } 
    }
}  
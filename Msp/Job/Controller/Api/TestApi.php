<?php 
namespace Msp\Job\Controller\Api;

//http://milandev.me/magento-2-how-to-get-external-api-value-in-the-ui-component-form-by-adding-a-custom-html-button/
class TestApi extends \Magento\Framework\App\Action\Action
{
    protected $resultPageFactory;

    protected $session;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory        )
    {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->resultJsonFactory = $resultJsonFactory;
    }
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        $params = $this->getRequest()->getParams();
        //echo "<pre>";print_r($data);exit;
        try {
                $result = $this->resultJsonFactory->create();
                $resultPage = $this->resultPageFactory->create();

                $data = '{"data":{"name":"Amit","mobile":9898749414}}';

                $adminToken = array(
                'Authorization: Bearer eyJraWQiOiIxIiwiYWxnIjoiSFMyNTYifQ.eyJ1aWQiOjEsInV0eXBpZCI6MiwiaWF0IjoxNjc1NjgzNTA1LCJleHAiOjE2NzU2ODcxMDV9.jmCpPhACTXxRCAwa_icF_UjgoLMdWGdGitYnSU5oJqE',
                'Content-Type: application/json'
                );
                // start api calling
                $service_url = 'http://202.131.107.107:8013/a_magento244/pub/rest/V1/custom-api/test/';
                $handle = curl_init();
     
                curl_setopt($handle, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
                curl_setopt($handle, CURLOPT_HTTPHEADER,$adminToken);
                curl_setopt($handle, CURLOPT_URL, $service_url);
                curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, false);
                curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($handle, CURLOPT_POSTFIELDS, $data);
     
                $response = curl_exec($handle);
                //$code = curl_getinfo($handle, CURLINFO_HTTP_CODE);
                curl_close($handle);
                $response = json_decode($response);         
                
                //echo "<pre>";print_r($response);exit;
                $result->setData(['data' => $response]);
                //$result->setData(['output' => $response]);
                return $result; 

        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            $this->messageManager->addError($e->getMessage());
        }
    }
}
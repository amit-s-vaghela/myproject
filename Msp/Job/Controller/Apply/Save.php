<?php 
namespace Msp\Job\Controller\Apply;
 
class Save extends \Magento\Framework\App\Action\Action
{
    protected $resultPageFactory;

    protected $session;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Msp\Job\Model\GridFactory $gridFactory
        )
    {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->gridFactory = $gridFactory;
    }
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();

            $data = $this->getRequest()->getPostValue();
            //echo "<pre>";print_r($data);exit();
            try 
            {

                    $result = $this->resultJsonFactory->create();
                    $resultPage = $this->resultPageFactory->create();
                    $currentProductId = $data;
                    $model = $this->gridFactory->create();
                    $model->addData($data);
                    $saveData = $model->save();
             
                    $block = $resultPage->getLayout()
                            ->createBlock('Msp\Job\Block\Index\View')
                            ->setTemplate('Msp_Job::view.phtml')
                            ->setData('data',$data)
                            ->toHtml();
             
                    $result->setData(['output' => $block]);
                    return $result; 
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            }
        }
}


        
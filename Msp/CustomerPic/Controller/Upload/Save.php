<?php
namespace Msp\CustomerPic\Controller\Upload;

use Magento\Framework\App\Action\Context;
//use Vky\Test\Model\TestFactory;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\MediaStorage\Model\File\UploaderFactory;
use Magento\Framework\Image\AdapterFactory;
use Magento\Framework\Filesystem;
use Magento\Customer\Model\Session;

class Save extends \Magento\Framework\App\Action\Action
{
    protected $_test;
    protected $uploaderFactory;
    protected $adapterFactory;
    protected $filesystem;

    public function __construct(
        Context $context,
        //TestFactory $test,
        UploaderFactory $uploaderFactory,
        AdapterFactory $adapterFactory,
        Filesystem $filesystem,
        Session $customerSession,
        \Magento\Customer\Model\Customer $customer,
        \Magento\Customer\Model\ResourceModel\CustomerFactory $customerFactory,
        \Magento\Framework\Message\ManagerInterface $messageManager
    ) {
       // $this->_test = $test;
        $this->uploaderFactory = $uploaderFactory;
        $this->adapterFactory = $adapterFactory;
        $this->filesystem = $filesystem;
        $this->customerSession = $customerSession;
        parent::__construct($context);
        $this->customer = $customer;
        $this->customerFactory = $customerFactory;
        $this->messageManager = $messageManager;
    }
    public function execute()
    {
        $customer_id = $this->customerSession->getCustomer()->getId();
        if(!empty($customer_id)){
        	$customer_image = $this->getRequest()->getFiles('profile_pic');
        	//echo "<pre>";print_r($customer_image);exit();
            $data = $this->getRequest()->getParams();
            if(isset($_FILES['profile_pic']['name']) && $_FILES['profile_pic']['name'] != '') {
                try{
                    $uploaderFactory = $this->uploaderFactory->create(['fileId' => 'profile_pic']);
                    $uploaderFactory->setAllowedExtensions(['jpg', 'jpeg', 'gif', 'png']);
                    $imageAdapter = $this->adapterFactory->create();
                    $uploaderFactory->addValidateCallback('custom_image_upload',$imageAdapter,'validateUploadFile');
                    $uploaderFactory->setAllowRenameFiles(true);
                    // $uploaderFactory->setFilesDispersion(true);
                    $mediaDirectory = $this->filesystem->getDirectoryRead(DirectoryList::MEDIA);
                    $destinationPath = $mediaDirectory->getAbsolutePath('customer/');
                    $folderPath = $destinationPath.$customer_id;
                    $result = $uploaderFactory->save($folderPath);
                   
                    $imagePath = '/'.$customer_id.'/'.$result['file'];
                    //echo "image Path ==>  ".$imagePath;exit;
                   if(!empty($result['file'])) {
                        $customer = $this->customer->load($customer_id);
                        $customerData = $customer->getDataModel();
                        $customerData->setCustomAttribute('profile_pic',$imagePath);
                        $customer->updateData($customerData);

                        $customerResource = $this->customerFactory->create();
                        $customerResource->saveAttribute($customer, 'profile_pic'); 
                        $message = __('Profile Picture updated Successfully....!'); 
                        $this->messageManager->addSuccessMessage($message);
                    }

                } catch (\Exception $e) {
                    $message = $e->getMessage();
                    $this->_getSession()->addError($message);
                }
            }
        
        }

        $this->_redirect('customer/account/');


    }
}
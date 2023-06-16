<?php
namespace Msp\AddPrice\Observer;

use Magento\Framework\Event\Observer;

class SaveCustomer implements \Magento\Framework\Event\ObserverInterface
{
    protected $request;

    public function __construct(
        \Magento\Framework\App\Request\Http $request,
        \Msp\AddPrice\Model\GridFactory $gridFactory
    )
    {
        $this->request = $request;
        $this->gridFactory = $gridFactory;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $writer = new \Zend_Log_Writer_Stream(BP . '/var/log/customer_data.log');
        $logger = new \Zend_Log();
        $logger->addWriter($writer);

        $data = $this->request->getPost();
       if ($data) {

            $model = $this->gridFactory->create();
            $model->setTelephone($data['telephone']);
            $model->setName($data['name']);
            $model->setEmail($data['email']);
            $model->setComment($data['comment']);
            $model->save();

            $logger->info(print_r($data, true));
            // var_dump($this->request->getPost());
            // die('test');
            return $this;
       }
                
    }

}
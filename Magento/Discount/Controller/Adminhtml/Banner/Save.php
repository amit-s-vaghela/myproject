<?php

namespace Magento\Discount\Controller\Adminhtml\Banner;

use Magento\Discount\Model\Banner;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;

class Save extends Action
{
    protected $request;
    protected $_moduleFactory;
    protected $resultRedirectFactory;
    protected $jsonHelper;
    protected $date;

    public function __construct(Context $context, Banner $moduleFactory, \Magento\Framework\Json\Helper\Data $jsonHelper, \Magento\Framework\Stdlib\DateTime\DateTime $date)
    {
        $this->jsonHelper = $jsonHelper;
        $this->date = $date;
        $this->_moduleFactory = $moduleFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();

        try {
            $id = (int) $this->getRequest()->getParam('id');
            $date = $this->date->gmtDate();
            $objectManager = $this->_objectManager->create('Magento\Discount\Model\Banner');
            if ($id) {
                $postdata = [
                    'link' => isset($data['link']) ? $data['link'] : null,
                    'image' => isset($data['image'][0]['name']) ? $data['image'][0]['name'] : null,
                    'updated_at' => $date,
                ];
                $objectManager->load($id);
                $objectManager->setData($postdata)->setId($id);
                $objectManager->save();
            } else {
                $postdata = [
                    'link' => isset($data['link']) ? $data['link'] : null,
                    'image' => isset($data['image'][0]['name']) ? $data['image'][0]['name'] : null,
                    'created_at' => $date,
                    'updated_at' => $date,
                ];
                $objectManager->setData($postdata);
                $objectManager->save();
                $this->messageManager->addSuccessMessage(__('The Banner has been saved.'));
            }
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(nl2br($e->getMessage()));

            return $resultRedirect->setPath('*/*/new');
        }
        if ($this->getRequest()->getParam('back')) {
            $this->messageManager->addSuccessMessage(__('The Banner has been saved.'));

            return $resultRedirect->setPath('*/*/new', ['id' => $id, '_current' => true]);
        }

        return $resultRedirect->setPath('*/*/index');
    }
}

<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 *
 * Created By: MageDelight Pvt. Ltd.
 */
namespace MD\UiExample\Controller\Adminhtml\Index;
use Magento\Backend\App\Action;

class Delete extends Action
{
    /**
     * @var \MD\UiExample\Model\Blog
     */
    protected $modelBlog;
    /**
     * @param Context                  $context
     * @param \MD\UiExample\Model\Blog $blogFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \MD\UiExample\Model\Blog $blogFactory
    ) {
        parent::__construct($context);
        $this->modelBlog = $model;
    }
    /**
     * {@inheritdoc}
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('MD_UiExample::index_delete');
    }
    /**
     * Delete action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('blog_id');
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($id) {
            try {
                $model = $this->modelBlog;
                $model->load($id);
                $model->delete();
                $this->messageManager->addSuccess(__('Record deleted successfully.'));
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
                return $resultRedirect->setPath('*/*/edit', ['id' => $id]);
            }
        }
        $this->messageManager->addError(__('Record does not exist.'));
        return $resultRedirect->setPath('*/*/');
    }
}
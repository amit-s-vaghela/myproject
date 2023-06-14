<?php
namespace Msp\Job\Observer;

use Magento\Framework\Event\ObserverInterface;


class CheckLoginPersistentObserver implements ObserverInterface
{
         /**
         * @var \Magento\Framework\App\Response\RedirectInterface
         */
        protected $redirect;

        /**
         * Customer session
         *
         * @var \Magento\Customer\Model\Session
         */
        protected $_customerSession;

        public function __construct(
            \Magento\Customer\Model\Session $customerSession,
             \Magento\Framework\UrlInterface $url,
            \Magento\Framework\App\Response\RedirectInterface $redirect

        ) {

            $this->_customerSession = $customerSession;
            $this->url = $url;
            $this->redirect = $redirect;

        }

        public function execute(\Magento\Framework\Event\Observer $observer)
        {
            // $actionName = $observer->getEvent()->getRequest()->getFullActionName();
            $controller = $observer->getControllerAction();

            // $openActions = array(
            //     'create',
            //     'createpost',
            //     'login',
            //     'loginpost',
            //     'logoutsuccess',
            //     'forgotpassword',
            //     'forgotpasswordpost',
            //     'resetpassword',
            //     'resetpasswordpost',
            //     'confirm',
            //     'confirmation'
            // );
            // if ($controller == 'account' && in_array($actionName, $openActions)) {
            //     return $this; //if in allowed actions do nothing.
            // }
            // if(!$this->_customerSession->isLoggedIn()) {
            //     return $this->redirect->redirect($controller->getResponse(), 'customer/account/login');
            // }

            // $resultRedirect->setUrl($this->url->getUrl('customer/account/login'));
            // return $resultRedirect;
            
            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

            $customerSession = $objectManager->get('\Magento\Customer\Model\Session');
            $urlInterface = $objectManager->get('\Magento\Framework\UrlInterface');

            if(!$customerSession->isLoggedIn()) {
                $customerSession->setAfterAuthUrl($urlInterface->getCurrentUrl());
                $customerSession->authenticate();
            }

        }

}
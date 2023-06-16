<?php
namespace Msp\AddPrice\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\App\RequestInterface;
 
class PricecalculationsAfterAddtoCart implements ObserverInterface
{
      protected $cart;

      public function __construct(
        \Magento\Checkout\Model\Cart $cart
      ){
         $this->cart = $cart;
      }
     
      
      public function execute(\Magento\Framework\Event\Observer $observer) {
          $item = $observer->getEvent()->getData('quote_item');
          $product = $observer->getEvent()->getData('product');
          $itemProId = $item->getProduct()->getId();
          $custom_price = $product->getPrice() + 10;
          $item->setCustomPrice($custom_price);
          $item->setOriginalCustomPrice($custom_price);
          $item->getProduct()->setIsSuperMode(true);

            // $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            // $cart = $objectManager->get('\Magento\Checkout\Model\Cart');
            $couponCode = 'FLAT20%';
            $quote = $this->cart->getQuote()->setCouponCode($couponCode)->collectTotals()->save();
            
          return $this;
      }
}
<?php
namespace SoftWebPos\MobileApi\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use Magedelight\Cgr\Helper\ProductHelper;

class ValidateRestrictedProducts implements ObserverInterface
{
    /**
     * @var ProductHelper
     */
    private $productHelper;
    
    /**
     * @param ProductHelper $productHelper
     */
    public function __construct(
        ProductHelper $productHelper
    ) {
        $this->productHelper = $productHelper;
    }

    /**
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     */

    public function execute(Observer $observer)
    {
        $quoteItem = $observer->getEvent()->getItem();
        
        if (!$quoteItem) {
            return;
        }

        if (!$quoteItem->getProductId() || !$quoteItem->getQuote() || $quoteItem->getQuote()->getIsSuperMode()) {
            return $this;
        }
        
        $product = $quoteItem->getProduct();
       
        if ($this->productHelper->isProductRestricted($product)) {
            $item = ($quoteItem->getParentItem())?
                $quoteItem->getParentItem():$quoteItem;

            $item->addErrorInfo(
                'cgr',
                '',
                __('This product is not available for this group.')
            );
            $item->getQuote()->addErrorInfo(
                'error',
                'cgr',
                '',
                __('Some of the products are not available for this group.')
            );
        }
    }
}

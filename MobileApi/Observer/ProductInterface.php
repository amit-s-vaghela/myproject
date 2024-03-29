<?php
namespace SoftWebPos\MobileApi\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Catalog\Api\ProductRepositoryInterfaceFactory as ProductRepository;
use Magento\Catalog\Helper\ImageFactory as ProductImageHelper;
use Magento\Store\Model\StoreManagerInterface as StoreManager;
use Magento\Store\Model\App\Emulation as AppEmulation;
use Magento\Quote\Api\Data\CartItemExtensionFactory;

class ProductInterface implements ObserverInterface
{   
    /**
     * @var ProductRepository
     */
    protected $productRepository;

    /**
     *@var \Magento\Catalog\Helper\ImageFactory
        */
    protected $productImageHelper;

    /**
     *@var \Magento\Store\Model\StoreManagerInterface
        */
    protected $storeManager;

    /**
     *@var \Magento\Store\Model\App\Emulation
        */
    protected $appEmulation;

    /**
     * @var CartItemExtensionFactory
     */
    protected $extensionFactory;

    /**
     * @param ProductRepository $productRepository
     * @param \Magento\Catalog\Helper\ImageFactory
     * @param \Magento\Store\Model\StoreManagerInterface
     * @param \Magento\Store\Model\App\Emulation
     * @param CartItemExtensionFactory $extensionFactory
     */
    public function __construct(
        ProductRepository $productRepository,
        ProductImageHelper $productImageHelper,
        StoreManager $storeManager,
        AppEmulation $appEmulation,
        CartItemExtensionFactory $extensionFactory
    ) {
        $this->productRepository = $productRepository;
        $this->productImageHelper = $productImageHelper;
        $this->storeManager = $storeManager;
        $this->appEmulation = $appEmulation;
        $this->extensionFactory = $extensionFactory;
    }

public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $quote = $observer->getQuote();

        /**
         * Code to add the items attribute to extension_attributes
         */
        foreach ($quote->getAllItems() as $quoteItem) {
            $product = $this->productRepository->create()->getById($quoteItem->getProductId());
            $itemExtAttr = $quoteItem->getExtensionAttributes();
            if ($itemExtAttr === null) {
                $itemExtAttr = $this->extensionFactory->create();
            }
            $imageurl = $this->getImageUrl($product, 'product_thumbnail_image');
            $itemExtAttr->setImageUrl($imageurl);
            $quoteItem->setExtensionAttributes($itemExtAttr);
        }
        return;
    }

    protected function getImageUrl($product, string $imageType = '')
    {
        $storeId = $this->storeManager->getStore()->getId();

        $this->appEmulation->startEnvironmentEmulation($storeId, \Magento\Framework\App\Area::AREA_FRONTEND, true);
        $imageUrl = $imageUrl = $this->productImageHelper->create()->init($product, $imageType)->getUrl();

        $this->appEmulation->stopEnvironmentEmulation();

        return $imageUrl;
    }
}
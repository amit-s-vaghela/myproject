<?php
namespace Vendic\OfflinePayments\Helper;

use Magento\Store\Model\StoreManagerInterface;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{

    /**
   * @var \Magento\Framework\App\Config\ScopeConfigInterface
   */
   protected $scopeConfig;

   /**
   * Recipient email config path
   */
   const QRIMAGE_PATH = 'payment/cash/image_upload';

   public function __construct(
    \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
    \Magento\Store\Model\StoreManagerInterface $storeManager
    )
   {
      $this->scopeConfig = $scopeConfig;
      $this->storeManager = $storeManager;
   }

   /**
   * Sample function returning config value
   **/

  public function getQRcodeImage() {
    $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;

    return $this->scopeConfig->getValue(self::QRIMAGE_PATH, $storeScope);


    }

    public function getBaseUrlMedia()
    {
       return $this->storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA)."qrcode/";
    }

}
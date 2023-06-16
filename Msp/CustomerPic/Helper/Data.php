<?php
namespace Msp\CustomerPic\Helper;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

class Data extends AbstractHelper
{
    const MODULE_ENABLE = 'customerpic/general/is_enable';

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    public function __construct(ScopeConfigInterface $scopeConfig)
    {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Get store config value
     *
     * @return string
     */
    public function getConfigValue($field, $storeId = null)
    {
        return $this->scopeConfig->getValue(
            $field,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Check is enable
     *
     * @return string
     */
    public function isEnable()
    {
        return $this->scopeConfig->getValue(
            self::MODULE_ENABLE,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );

        // $configValue = $this->scopeConfig->getValue(self::XML_CONFIG_PATH,ScopeInterface::SCOPE_STORE);
        // return $configValue;
    }
}
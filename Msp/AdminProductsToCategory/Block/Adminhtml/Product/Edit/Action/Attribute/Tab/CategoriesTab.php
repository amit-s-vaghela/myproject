<?php
namespace Msp\AdminProductsToCategory\Block\Adminhtml\Product\Edit\Action\Attribute\Tab;


class CategoriesTab extends \Magento\Backend\Block\Widget implements \Magento\Backend\Block\Widget\Tab\TabInterface
{
    /**
     * Tab settings
     *
     * @return \Magento\Framework\Phrase
     */
    public function getTabLabel()
    {
        return __('Categories Tree');
    }

    /**
     * @return \Magento\Framework\Phrase
     */
    public function getTabTitle()
    {
        return __('Categories Tree');
    }

    /**
     * @return bool
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * @return bool
     */
    public function isHidden()
    {
        return false;
    }

    // public function getTabUrl()
    // {
    //     return $this->getUrl('category/index/add', ['_current' => true]);
    // }
}

<?php

namespace Magento\Discount\Model;

use Magento\Framework\Model\AbstractModel;

class Banner extends AbstractModel
{
    /**
     * Construct.
     */
    protected function _construct()
    {
        $this->_init(\Magento\Discount\Model\ResourceModel\Banner::class);
    }
}

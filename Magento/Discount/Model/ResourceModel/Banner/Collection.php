<?php

namespace Magento\Discount\Model\ResourceModel\Banner;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    /**
     * Define resource model.
     */
    protected function _construct()
    {
        $this->_init(\Magento\Discount\Model\Banner::class, \Magento\Discount\Model\ResourceModel\Banner::class);
    }
}

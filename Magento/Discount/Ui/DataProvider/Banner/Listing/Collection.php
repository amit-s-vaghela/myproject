<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Discount\Ui\DataProvider\Banner\Listing;

use Magento\Framework\View\Element\UiComponent\DataProvider\SearchResult;

class Collection extends SearchResult
{
    /**
     * Override _initSelect to add custom columns.
     */
    protected function _initSelect()
    {
        $this->addFilterToMap('id', 'main_table.id');
        // $this->addFilterToMap('name', 'magentoblogname.value');
        parent::_initSelect();


    }

    
}

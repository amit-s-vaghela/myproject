<?php 
namespace Dolphin\Job\Model\ResourceModel\Grid;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'id';
    /**
     * Define resource model.
     */
    protected function _construct()
    {
        $this->_init(
            'Dolphin\Job\Model\Grid',
            'Dolphin\Job\Model\ResourceModel\Grid'
        );
    }
}

<?php 
namespace Msp\Job\Model\ResourceModel\Grid;

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
            'Msp\Job\Model\Grid',
            'Msp\Job\Model\ResourceModel\Grid'
        );
    }
}

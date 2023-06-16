<?php 
namespace Msp\AddPrice\Model\ResourceModel\Grid;

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
            'Msp\AddPrice\Model\Grid',
            'Msp\AddPrice\Model\ResourceModel\Grid'
        );
    }
}

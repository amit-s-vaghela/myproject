<?php
namespace Msp\AddPrice\Setup;

use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;

class UpgradeData implements UpgradeDataInterface

{
    private $eavSetupFactory;

    public function __construct(
        EavSetupFactory $eavSetupFactory
    
    )
    {
        $this->eavSetupFactory = $eavSetupFactory;
       
    }

    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)

    {

        if (version_compare($context->getVersion(), '1.0.3') < 0) {
            $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);

            $eavSetup->removeAttribute(\Magento\Catalog\Model\Product::ENTITY, 'custom_yes_no');
            $eavSetup->addAttribute(\Magento\Catalog\Model\Product::ENTITY, 'custom_yes_no', [
                'group' => 'Product Details',
                'type' => 'int',
                'sort_order' => 200,
                'backend' => '',
                'frontend' => '',
                'label' => 'Custom Yes/No',
                'input' => 'boolean',
                'class' => '',
                'source' => 'Magento\Eav\Model\Entity\Attribute\Source\Boolean',
                'global' => \Magento\Catalog\Model\ResourceModel\Eav\Attribute::SCOPE_GLOBAL,
                'visible' => true,
                'required' => false,
                'user_defined' => false,
                'default' => '',
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => true,
                'unique' => false,
                'apply_to' => ''
            ]); 
        
        }
        if (version_compare($context->getVersion(), '1.0.2') < 0) {
            //$this->removeCustomerAttribute($setup);
            $this->removeProductAttribute($setup);
        }

    }
    // private function removeCustomerAttribute(ModuleDataSetupInterface $setup)
    // {
    //     $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);

    //     $eavSetup->removeAttribute(
    //         \Magento\Customer\Model\Customer::ENTITY,
    //         'customer_attribute'
    //     );
    // }
    private function removeProductAttribute($setup)
    {
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);

        $eavSetup->removeAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'custom_yes_no'
        );
    }


}
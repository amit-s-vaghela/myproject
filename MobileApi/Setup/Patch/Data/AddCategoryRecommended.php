<?php
declare(strict_types=1);

namespace SoftWebPos\MobileApi\Setup\Patch\Data;

use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\Product\Type;
use Psr\Log\LoggerInterface;

class AddCategoryRecommended implements DataPatchInterface
{
    /**
     * @var ModuleDataSetupInterface
     */
    private ModuleDataSetupInterface $moduleDataSetup;

    /**
     * @var EavSetupFactory
     */
    private EavSetupFactory $eavSetupFactory;

    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param EavSetupFactory $eavSetupFactory
     * @param LoggerInterface $logger
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        EavSetupFactory $eavSetupFactory,
        LoggerInterface $logger
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->eavSetupFactory = $eavSetupFactory;
        $this->logger = $logger;
    }

    /**
     * @return void
     */
    public function apply()
    {
        try {
            $productTypes = implode(',', [Type::TYPE_SIMPLE, Type::TYPE_VIRTUAL]);

            /** @var EavSetup $eavSetup */
            $eavSetup = $this->eavSetupFactory->create(['setup' => $this->moduleDataSetup]);
            $eavSetup->addAttribute(
                \Magento\Catalog\Model\Category::ENTITY,
                'is_mobile',
                [
                    'type' => 'int',
                    'label' => 'Is Mobile Category',
                    'input' => 'select',
                    'sort_order' => 333,
                    'source' => 'Magento\Eav\Model\Entity\Attribute\Source\Boolean',
                    'global' => 1,
                    'visible' => true,
                    'required' => false,
                    'user_defined' => false,
                    'default' => null,
                    'group' => 'General Information',
                    'backend' => ''
                ]
            );
            
        } catch (\Exception $e) {
            $this->logger->critical($e);
        }
    }

    /**
     * @return array
     */
    public static function getDependencies(): array
    {
        return [];
    }

    /**
     * @return array
     */
    public function getAliases(): array
    {
        return [];
    }
}
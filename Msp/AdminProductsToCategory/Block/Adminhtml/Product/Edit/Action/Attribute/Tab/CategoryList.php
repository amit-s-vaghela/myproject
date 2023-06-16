<?php
namespace Msp\AdminProductsToCategory\Block\Adminhtml\Product\Edit\Action\Attribute\Tab;


use Magento\Backend\Block\Template;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Catalog\Ui\Component\Product\Form\Categories\Option;
use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory as CategoryCollectionFactory;
use Magento\Catalog\Model\Category as CategoryModel;
class CategoryList extends Template
{
       
    public function __construct(
        Context $context,
        Json $json,
        CategoryCollectionFactory $categoryCollectionFactory,
        array $data = []
    ) {
        $this->json = $json;
        $this->categoryCollectionFactory = $categoryCollectionFactory;

        parent::__construct($context, $data);
    }

    /**
     * @return string
     */
    public function getConfig()
    {
        return $this->json->serialize($this->config->getConfig());
    }

    // public function getSerializeCategoriesOption()
    // {
    //     $objectManager = \Magento\Framework\App\ObjectManager::getInstance();

    //     $categories = $objectManager->create(
    //             'Magento\Catalog\Ui\Component\Product\Form\Categories\Options'
    //         )->toOptionArray();
    //     echo "<pre>";print_r($categories);echo "</pre>";exit();
    //     return $getCategoriesTree =json_encode($categories);
         
    // }

    public function getSerializeCategoriesOption()
     {  $storeId = 1;
         if (1) {
             /* @var $matchingNamesCollection \Magento\Catalog\Model\ResourceModel\Category\Collection */
             $matchingNamesCollection = $this->categoryCollectionFactory->create();
 
             $matchingNamesCollection->addAttributeToSelect('path')
                 ->addAttributeToFilter('entity_id', ['neq' => CategoryModel::TREE_ROOT_ID])
                 ->setStoreId($storeId);
 
             $shownCategoriesIds = [];
 
             foreach ($matchingNamesCollection as $category) {
                 foreach (explode('/', $category->getPath()) as $parentId) {
                     $shownCategoriesIds[$parentId] = 1;
                 }
             }
 
             /* @var $collection \Magento\Catalog\Model\ResourceModel\Category\Collection */
             $collection = $this->categoryCollectionFactory->create();
 
             $collection->addAttributeToFilter('entity_id', ['in' => array_keys($shownCategoriesIds)])
                 ->addAttributeToSelect(['name', 'is_active', 'parent_id'])
                 ->setStoreId($storeId);
 
             $categoryById = [
                 CategoryModel::TREE_ROOT_ID => [
                     'value' => CategoryModel::TREE_ROOT_ID
                 ],
             ];
 
             foreach ($collection as $category) {
                 foreach ([$category->getId(), $category->getParentId()] as $categoryId) {
                     if (!isset($categoryById[$categoryId])) {
                         $categoryById[$categoryId] = ['value' => $categoryId];
                     }
                 }
 
                 $categoryById[$category->getId()]['is_active'] = $category->getIsActive();
                 $categoryById[$category->getId()]['label'] = $category->getName();
                 $categoryById[$category->getParentId()]['optgroup'][] = &$categoryById[$category->getId()];
             }
 
             $this->categoriesTree = $categoryById[CategoryModel::TREE_ROOT_ID]['optgroup'];
         }

        //echo "<pre>";print_r($this->categoriesTree);echo "</pre>";exit();
        return $getCategoriesTree =json_encode($this->categoriesTree);
     }


}
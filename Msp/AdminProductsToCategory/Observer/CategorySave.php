<?php
namespace Msp\AdminProductsToCategory\Observer;

use Magento\Backend\App\Action;

/**
 * Class Save
 */
 class CategorySave
    implements \Magento\Framework\Event\ObserverInterface
{

    /**
     * @var  \Magento\Catalog\Api\CategoryLinkManagementInterface
     */
    protected $categoryLinkManagement;

    /**
     * @var      \Magento\Framework\Message\ManagerInterface
     */
    protected $messageManager;

    /**
     * @var
     */
    protected $productCollection;

     /**
      *  @var \Magento\Catalog\Helper\Product\Edit\Action\Attribute
      */
     protected $attributeHelper;


     protected $request;

    /**
     * Save constructor.
     * @param Action\Context $context
     * @param \Magento\Catalog\Api\CategoryLinkManagementInterface $categoryLinkManagement
     * @param \Magento\Framework\Message\ManagerInterface $messageManager
     * @param \Magento\Catalog\Helper\Product\Edit\Action\Attribute $attributeHelper
     * @param \Magento\Framework\App\Request\Http $request
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Catalog\Api\CategoryLinkManagementInterface $categoryLinkManagement,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Magento\Framework\App\Request\Http $request,
        \Magento\Catalog\Helper\Product\Edit\Action\Attribute $attributeHelper
    ) {
        $this->categoryLinkManagement = $categoryLinkManagement;
        $this->messageManager = $messageManager;
        $this->attributeHelper = $attributeHelper;
        $this->request = $request;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        //$order = $observer->getRequest()->getPost();
        $order = $this->getRequest()->getParams();

        //echo "<pre>";print_R($order);die;
        
        
        $active_tab = $this->getRequest()->getParam('active_tab');
        $categoryRemoveData = $this->getRequest()->getParam('remove_category_ids');
        $categoryAddData = $this->getRequest()->getParam('add_category_ids');
        
        try {
            if ($active_tab == "update_categories" && !empty($categoryAddData)) {
                if (!empty($categoryAddData)) {
                    $this->addCategoryToProduct($categoryAddData);
                }               
            }
            if ($active_tab == "update_categories" && !empty($categoryRemoveData)) {
                if (!empty($categoryRemoveData)) {
                    $this->removeCategoryToProduct($categoryRemoveData);
                }
            }

            $this->messageManager
                    ->addSuccess(__(
                        'A total of %1 record(s) were updated.',
                        count($this->attributeHelper->getProductIds())
                    ));

        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            $this->messageManager->addError($e->getMessage());

        } 
    }

    public function addCategoryToProduct($categoryIds=[]){
        if(!count($categoryIds)){
            return;
        }
        $productCollection = $this->getProductCollection();
        foreach($productCollection as $product) {
            $categoryIdsArray = array_unique(
                array_merge($categoryIds, $product->getCategoryIds()),
                SORT_STRING
            );
            $this->categoryLinkManagement->assignProductToCategories(
                $product->getSku(),
                $categoryIdsArray
            );
        }
    }

   
    public function removeCategoryToProduct($categoryIds=[]){
        if(!count($categoryIds)){
            return;
        }
        $productCollection = $this->getProductCollection();
        foreach($productCollection as $product) {
            $categoryIdsArray = array_diff($product->getCategoryIds(), $categoryIds);
            $this->categoryLinkManagement->assignProductToCategories(
                $product->getSku(),
                $categoryIdsArray
            );
        }
    }
    protected function getRequest()
     {
         return $this->request;
     }

    public function getProductCollection(){
        if(!$this->productCollection){
            $this->productCollection = $this->attributeHelper->getProducts();
        }
        return $this->productCollection;
    }
}
